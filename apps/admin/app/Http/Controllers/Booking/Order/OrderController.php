<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Order;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Order\SearchRequest;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Order\Order;
use App\Admin\Repositories\OrderAdministratorRepository;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Booking\BookingAdapter;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Module\Shared\Enum\SourceEnum;

class OrderController extends Controller
{
    protected Prototype $prototype;

    public function __construct(
        private readonly OrderAdministratorRepository $administratorRepository
    ) {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
    }

    //@todo

    /**
     * Юзкейсы
     * 1. Получить заказ
     * 2. Получить список броней заказа (без деталей)
     * 3. Получить статусы заказа
     * 4. Получить доступные действия с заказом
     * 5. Получить список ваучеров
     * 6. Получить список инвоисов
     */

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

    public function show(int $id): LayoutContract
    {
        $booking = BookingAdapter::getBooking($id);
        $order = OrderAdapter::findOrder($booking->orderId);
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
                'creator' => Administrator::find($booking->creatorId),
            ]);
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $orders = OrderAdapter::getActiveOrders($request->getClientId());

        return response()->json($orders);
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
                'required'=> true,
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
            ->number('order_id', ['label' => '№ Заказа'])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => ''])
            ->select('manager_id', ['label' => 'Менеджер', 'items' => Administrator::all(), 'emptyItem' => ''])
            ->select('status', ['label' => 'Статус', 'items' => OrderAdapter::getStatuses(), 'emptyItem' => ''])
            ->enum('source', ['label' => 'Источник', 'enum' => SourceEnum::class, 'emptyItem' => ''])
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
