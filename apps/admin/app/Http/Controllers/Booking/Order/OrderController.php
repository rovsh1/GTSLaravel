<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Order;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Booking\UpdateManagerRequest;
use App\Admin\Http\Requests\Order\SearchRequest;
use App\Admin\Http\Requests\Order\UpdateClientPenaltyRequest;
use App\Admin\Http\Requests\Order\UpdateExternalIdRequest;
use App\Admin\Http\Requests\Order\UpdateNoteRequest;
use App\Admin\Http\Requests\Order\UpdateStatusRequest;
use App\Admin\Http\Resources\Order\Booking;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Order\Order;
use App\Admin\Repositories\OrderAdministratorRepository;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\SourceEnum;
use Sdk\Shared\Exception\ApplicationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    protected Prototype $prototype;

    public function __construct(
        private readonly OrderAdministratorRepository $administratorRepository
    ) {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
    }

    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $grid = $this->gridFactory();
        $query = $this->prepareGridQuery(Order::query(), $grid->getSearchCriteria());
        $grid->data($query);

        return Layout::title($this->prototype->title('index'))
            ->view($this->prototype->view('index') ?? $this->prototype->view('grid') ?? 'default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator(),
                'createUrl' => $this->isAllowed('create') ? $this->prototype->route('create') : null
            ]);
    }

    public function create(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype)
            ->add($this->prototype->title('create'));

        $form = $this->formFactory()
            ->method('post')
            ->action($this->prototype->route('store'));

        return Layout::title($this->prototype->title('create'))
            ->view($this->prototype->view('form'), [
                'form' => $form,
                'cancelUrl' => $this->prototype->route('index'),
            ]);
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post')
            ->failUrl($this->prototype->route('create'));

        $form->submitOrFail();

        $data = $form->getData();
        $creatorId = request()->user()->id;
        $managerId = $data['manager_id'] ?? $creatorId;
        $currency = $data['currency'] ? CurrencyEnum::from($data['currency']) : null;
        if ($currency === null) {
            $client = Client::find($data['client_id']);
            $currency = $client->currency;
        }
        try {
            $orderId = OrderAdapter::create(
                clientId: $data['client_id'],
                legalId: $data['legal_id'],
                currency: $currency ?? $client->currency,
                managerId: $managerId,
                creatorId: $creatorId,
                note: $data['note'] ?? null,
            );
        } catch (ApplicationException $e) {
            $form->throwException($e);
        }

        return redirect(
            $this->prototype->route('show', $orderId)
        );
    }

    public function show(int $id): LayoutContract
    {
        $order = OrderAdapter::getOrder($id);
        if ($order === null) {
            throw new NotFoundHttpException('Order not found');
        }
        $client = Client::find($order->clientId);

        $title = "Заказ №{$id}";
        Breadcrumb::prototype($this->prototype)
            ->add($title);

        return Layout::title($title)
            ->view($this->prototype->view('show'), [
                'orderId' => $id,
                'model' => $order,
                'client' => $client,
                'manager' => $this->administratorRepository->get($id),
                'creator' => Administrator::find($order->creatorId),
                'serviceBookingCreate' => route('service-booking.create'),
                'hotelBookingCreate' => route('hotel-booking.create'),
            ]);
    }

    public function get(int $id): JsonResponse
    {
        $order = OrderAdapter::getOrder($id);

        return response()->json($order);
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $orders = OrderAdapter::getActiveOrders($request->getClientId());

        return response()->json($orders);
    }

    public function bookings(int $orderId): JsonResponse
    {
        $bookings = OrderAdapter::getBookings($orderId);

        return response()->json(
            Booking::collection($bookings)
        );
    }

    public function getAvailableActions(int $orderId): JsonResponse
    {
        return response()->json(
            OrderAdapter::getAvailableActions($orderId)
        );
    }

    public function getStatuses(): JsonResponse
    {
        return response()->json(
            OrderAdapter::getStatuses()
        );
    }

    public function updateStatus(UpdateStatusRequest $request, int $id): JsonResponse
    {
        return response()->json(
            OrderAdapter::updateStatus($id, $request->getStatus(), $request->getRefundFee())
        );
    }

    public function updateManager(UpdateManagerRequest $request, int $id): AjaxResponseInterface
    {
        $this->administratorRepository->update($id, $request->getManagerId());

        return new AjaxSuccessResponse();
    }

    public function updateNote(UpdateNoteRequest $request, int $id): AjaxResponseInterface
    {
        OrderAdapter::updateNote($id, $request->getNote());

        return new AjaxSuccessResponse();
    }

    public function updateExternalId(UpdateExternalIdRequest $request, int $id): AjaxResponseInterface
    {
        OrderAdapter::updateExternalId($id, $request->getExternalId());

        return new AjaxSuccessResponse();
    }

    public function updateClientPenalty(UpdateClientPenaltyRequest $request, int $id): AjaxResponseInterface
    {
        OrderAdapter::updateClientPenalty($id, $request->getClientPenalty());

        return new AjaxSuccessResponse();
    }

    private function prepareGridQuery(Builder $query, array $searchCriteria): Builder
    {
        return $query
            ->applyCriteria($searchCriteria)
            ->addSelect('orders.*')
            ->join('clients', 'clients.id', '=', 'orders.client_id')
            ->addSelect('clients.name as client_name')
            ->leftJoin('administrator_orders', 'administrator_orders.order_id', '=', 'orders.id')
            ->leftJoin('administrators', 'administrators.id', '=', 'administrator_orders.administrator_id')
            ->addSelect('administrators.presentation as manager_name');
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->id('id', [
                'text' => '№',
                'order' => true,
                'route' => $this->prototype->routeName('show')
            ])
            ->orderStatus('status', ['text' => 'Статус', 'statuses' => OrderAdapter::getStatuses(), 'order' => true])
            ->text('client_name', ['text' => 'Клиент'])
            ->text('manager_name', ['text' => 'Менеджер'])
            ->text('date_start', ['text' => 'Период'])
            ->enum('source', ['text' => 'Источник', 'order' => true, 'enum' => SourceEnum::class])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->orderBy('created_at', 'desc')
            ->paginator(20);
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('client_id', [
                'label' => __('label.client'),
                'emptyItem' => '',
                'items' => Client::orderBy('name')->get(),
                'required' => true,
            ])
            ->hidden('legal_id', [
                'label' => 'Юр. лицо',
            ])
            ->currency('currency', [
                'label' => 'Валюта',
                'emptyItem' => '',
            ])
            ->manager('manager_id', [
                'label' => 'Менеджер',
                'emptyItem' => '',
                'value' => request()->user()->id,
            ])
            ->textarea('note', ['label' => 'Примечание']);
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->text('order_id', ['label' => '№ Заказа', 'type' => 'number'])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => ''])
            ->select('manager_id', ['label' => 'Менеджер', 'items' => Administrator::all(), 'emptyItem' => ''])
            ->select('status', ['label' => 'Статус', 'items' => OrderAdapter::getStatuses(), 'emptyItem' => ''])
            ->enum('source', ['label' => 'Источник', 'enum' => SourceEnum::class, 'emptyItem' => ''])
            ->dateRange('start_period', ['label' => 'Дата заезда'])
            ->dateRange('end_period', ['label' => 'Дата выезда'])
            ->dateRange('created_period', ['label' => 'Дата создания']);
    }

    protected function isAllowed(string $permission): bool
    {
        return $this->prototype->hasPermission($permission) && Acl::isAllowed($this->prototype->key, $permission);
    }

    protected function getPrototypeKey(): string
    {
        return 'booking-order';
    }
}
