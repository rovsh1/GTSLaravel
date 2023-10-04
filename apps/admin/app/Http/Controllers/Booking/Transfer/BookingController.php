<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Transfer;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Booking\Hotel\BulkDeleteRequest;
use App\Admin\Http\Requests\Booking\Hotel\UpdateManagerRequest;
use App\Admin\Http\Requests\Booking\Hotel\UpdateNoteRequest;
use App\Admin\Http\Requests\Booking\Hotel\UpdatePriceRequest;
use App\Admin\Http\Requests\Booking\Hotel\UpdateStatusRequest;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Reference\Currency;
use App\Admin\Models\Supplier\TransferService;
use App\Admin\Repositories\BookingAdministratorRepository;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Booking\HotelAdapter;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Booking\Transfer\PriceAdapter;
use App\Admin\Support\Facades\Booking\TransferAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Core\Support\Http\Responses\AjaxRedirectResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Shared\Application\Exception\ApplicationException;
use Module\Shared\Enum\SourceEnum;

class BookingController extends Controller
{
    protected Prototype $prototype;

    public function __construct(
        private readonly BookingAdministratorRepository $administratorRepository
    ) {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
    }

    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $requestableStatuses = array_map(fn(BookingStatusEnum $status) => $status->value,
            RequestRules::getRequestableStatuses());

        $grid = $this->gridFactory();
        $data = TransferAdapter::getBookingQuery()
            ->applyCriteria($grid->getSearchCriteria())
            ->join('administrator_bookings', 'administrator_bookings.booking_id', '=', 'bookings.id')
            ->join('administrators', 'administrators.id', '=', 'administrator_bookings.administrator_id')
            ->addSelect('administrators.presentation as manager_name')
            ->addSelect(
                DB::raw('(SELECT bookings.status IN (' . implode(',', $requestableStatuses) . ')) as is_requestable'),
            )
            ->addSelect(
                DB::raw(
                    'EXISTS(SELECT 1 FROM booking_requests WHERE bookings.id = booking_requests.booking_id AND is_archive = 0) as has_downloadable_request'
                ),
            );

        $grid->data($data);

        return Layout::title('Брони транспортных услуг')
            ->view($this->prototype->view('index'), [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator(),
                'createUrl' => route('transfer-booking.create')
            ]);
    }

    public function create(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype)
            ->add($this->prototype->title('create') ?? 'Новая запись');

        $form = $this->formFactory()
            ->method('post')
            ->action(route('transfer-booking.store'));

        return Layout::title('Новая бронь')
            ->view($this->prototype->view('form'), [
                'form' => $form,
                'cancelUrl' => route('transfer-booking.index')
            ]);
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post');

        $form->trySubmit(route('transfer-booking.store'));

        $data = $form->getData();
        $creatorId = request()->user()->id;
        $orderId = $data['order_id'] ?? null;
        $currencyId = $data['currency_id'] ?? null;
        if ($orderId !== null && $currencyId === null) {
            $order = OrderAdapter::findOrder($orderId);
            $currencyId = $order->currency->id;
        }
        $client = Client::find($data['client_id']);
        $bookingId = TransferAdapter::createBooking(
            cityId: $data['city_id'],
            clientId: $data['client_id'],
            legalId: $data['legal_id'],
            currencyId: $currencyId ?? $client->currency_id,
            serviceId: $data['service_id'],
            creatorId: $creatorId,
            orderId: $data['order_id'] ?? null,
            note: $data['note'] ?? null
        );
        $this->administratorRepository->create($bookingId, $creatorId);

        return redirect(
            route('transfer-booking.show', $bookingId)
        );
    }

    public function edit(int $id): LayoutContract
    {
        $breadcrumbs = Breadcrumb::prototype($this->prototype);

        $booking = TransferAdapter::getBooking($id);

        $title = "Бронь №{$id}";
        $breadcrumbs->addUrl($this->prototype->route('show', $id), $title);
        $breadcrumbs->add($this->prototype->title('edit') ?? 'Редактирование');

        $form = $this->formFactory(true)
            ->method('put')
            ->action($this->prototype->route('update', $id))
            ->data($this->prepareFormData($booking));

        return Layout::title($title)
            ->view($this->prototype->view('form'), [
                'model' => $booking,
                'form' => $form,
                'cancelUrl' => $this->prototype->route('show', $id),
//                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null,
            ]);
    }

    public function update(int $id): RedirectResponse
    {
        $form = $this->formFactory(true)
            ->method('put')
            ->failUrl($this->prototype->route('edit', $id));

        $form->trySubmit();

        $data = $form->getData();
        try {
            TransferAdapter::updateBooking(
                id: $id,
                date: new Carbon($data['date'] . ' ' . $data['time']),
                note: $data['note'] ?? null,
                flightNumber: $data['flight_number']
            );
            $this->administratorRepository->update($id, $data['manager_id'] ?? request()->user()->id);
        } catch (ApplicationException $e) {
            $form->throwException($e);
        }

        return redirect($this->prototype->route('show', $id));
    }

    public function show(int $id): LayoutContract
    {
        $title = "Бронь №{$id}";

        $booking = TransferAdapter::getBooking($id);
        $order = OrderAdapter::findOrder($booking->orderId);
        $client = Client::find($order->clientId);

        Breadcrumb::prototype($this->prototype)
            ->add($title);

        return Layout::title($title)
            ->view($this->prototype->view('show'), [
                'bookingId' => $id,
                'model' => $booking,
                'client' => $client,
                'order' => $order,
                'currencies' => Currency::get(),
                'manager' => $this->administratorRepository->get($id),
                'creator' => Administrator::find($booking->creatorId),
                'editUrl' => $this->isAllowed('update') ? $this->prototype->route('edit', $id) : null,
                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null,
            ]);
    }

    public function get(int $id): JsonResponse
    {
        return response()->json(
            TransferAdapter::getBooking($id)
        );
    }

    public function getAvailableActions(int $id): JsonResponse
    {
        return response()->json(
            TransferAdapter::getAvailableActions($id)
        );
    }

    public function getStatuses(): JsonResponse
    {
        return response()->json(
            TransferAdapter::getStatuses()
        );
    }

    public function updateStatus(UpdateStatusRequest $request, int $id): JsonResponse
    {
        return response()->json(
            TransferAdapter::updateStatus(
                id: $id,
                status: $request->getStatus(),
                notConfirmedReason: $request->getNotConfirmedReason() ?? '',
                cancelFeeAmount: $request->getCancelFeeAmount()
            )
        );
    }

    public function getStatusHistory(int $id): JsonResponse
    {
        return response()->json(
            TransferAdapter::getStatusHistory($id)
        );
    }

    public function destroy(int $id): AjaxResponseInterface
    {
        TransferAdapter::deleteBooking($id);

        return new AjaxRedirectResponse($this->prototype->route());
    }

    public function bulkDelete(BulkDeleteRequest $request): AjaxResponseInterface
    {
        TransferAdapter::bulkDeleteBookings($request->getIds());

        return new AjaxSuccessResponse();
    }

    public function updatePrice(UpdatePriceRequest $request, int $id): AjaxResponseInterface
    {
        $grossPrice = $request->getGrossPrice();
        $netPrice = $request->getNetPrice();

        if ($request->isGrossPriceExists() && $grossPrice === null) {
            PriceAdapter::setCalculatedGrossPrice($id);
        }
        if ($request->isNetPriceExists() && $netPrice === null) {
            PriceAdapter::setCalculatedNetPrice($id);
        }

        if ($grossPrice !== null) {
            PriceAdapter::setGrossPrice($id, $grossPrice);
        }
        if ($netPrice !== null) {
            PriceAdapter::setNetPrice($id, $netPrice);
        }
        if ($request->isGrossPenaltyExists()) {
            PriceAdapter::setGrossPenalty($id, $request->getGrossPenalty());
        }
        if ($request->isNetPenaltyExists()) {
            PriceAdapter::setNetPenalty($id, $request->getNetPenalty());
        }

        return new AjaxSuccessResponse();
    }

    public function updateNote(int $id, UpdateNoteRequest $request): AjaxResponseInterface
    {
        TransferAdapter::updateNote($id, $request->getNote());

        return new AjaxSuccessResponse();
    }

    public function updateManager(int $id, UpdateManagerRequest $request): AjaxResponseInterface
    {
        $this->administratorRepository->update($id, $request->getManagerId());

        return new AjaxSuccessResponse();
    }

    public function copy(int $id): RedirectResponse
    {
        $newBookingId = TransferAdapter::copyBooking($id);

        $administrator = $this->administratorRepository->get($id);
        $this->administratorRepository->create($newBookingId, $administrator->id);

        return redirect(
            route('transfer-booking.show', $newBookingId)
        );
    }

    protected function formFactory(bool $isEdit = false): FormContract
    {
        return Form::name('data')
            ->select('client_id', [
                'label' => __('label.client'),
                'required' => !$isEdit,
                'emptyItem' => '',
                'items' => Client::orderBy('name')->get(),
                'disabled' => $isEdit,
            ])
            ->hidden('order_id', [
                'label' => 'Заказ',
                'disabled' => $isEdit
            ])
            ->hidden('legal_id', [
                'label' => 'Юр. лицо',
            ])
            ->currency('currency_id', [
                'label' => 'Валюта',
                'emptyItem' => '',
            ])
            ->city('city_id', [
                'label' => __('label.city'),
                'emptyItem' => '',
                'required' => true,
            ])
            ->hidden('service_id', [
                'label' => 'Услуга',
                'required' => true,
            ])
            ->manager('manager_id', [
                'label' => 'Менеджер',
                'emptyItem' => '',
                'value' => request()->user()->id,
            ])
            ->textarea('note', ['label' => 'ФИО клиента и место подачи/отправки авто']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->checkbox('checked', ['checkboxClass' => 'js-select-booking', 'dataAttributeName' => 'booking-id'])
            ->id('id', [
                'text' => '№',
                'order' => true,
                'renderer' => function ($row, $val) {
                    $bookingUrl = route('transfer-booking.show', $row['id']);
                    $idLink = "<a href='{$bookingUrl}'>{$row['id']}</a>";
                    $orderId = $row['order_id'];
                    $orderUrl = route('booking-order.show', $orderId);
                    $orderLink = "<a href='{$orderUrl}'>{$orderId}</a>";

                    return "$idLink / {$orderLink}";
                }
            ])
            ->bookingStatus(
                'status',
                ['text' => 'Статус', 'statuses' => TransferAdapter::getStatuses(), 'order' => true]
            )
            ->text('client_name', ['text' => 'Клиент'])
            ->text('city_name', ['text' => 'Город'])
            ->text('service_name', ['text' => 'Название услуги'])
            ->date('date', ['text' => 'Дата прилёта/вылета'])
            ->text('source', ['text' => 'Источник', 'order' => true])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->text('actions', ['renderer' => fn($row, $val) => $this->getActionButtons($row)])
            ->orderBy('created_at', 'desc')
            ->paginator(20);
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->number('order_id', ['label' => '№ Заказа'])
            ->country('country_id', ['label' => 'Страна', 'default' => '1'])
            ->city('city_id', ['label' => 'Город', 'emptyItem' => ''])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => ''])
            ->select('service_id', ['label' => 'Услуга', 'emptyItem' => '', 'items' => TransferService::get()])
            ->select('manager_id', ['label' => 'Менеджер', 'items' => Administrator::all(), 'emptyItem' => ''])
            ->select('status', ['label' => 'Статус', 'items' => HotelAdapter::getStatuses(), 'emptyItem' => ''])
            ->enum('source', ['label' => 'Источник', 'enum' => SourceEnum::class, 'emptyItem' => ''])
            ->dateRange('date_period', ['label' => 'Дата прилета/вылета'])
            ->dateRange('created_period', ['label' => 'Дата создания']);
    }

    private function prepareFormData(object $booking): array
    {
        $order = OrderAdapter::findOrder($booking->orderId);
        $manager = $this->administratorRepository->get($booking->id);

        return [
            'manager_id' => $manager->id,
            'order_id' => $booking->orderId,
            'currency_id' => $order->currency->id,
            'service_id' => $booking->serviceInfo->id,
            'client_id' => $order->clientId,
            'legal_id' => $order->legalId,
            'date' => $booking->date->format('Y-m-d'),
            'time' => $booking->date->format('H:i'),
            'note' => $booking->note,
        ];
    }

    private function getActionButtons(mixed $tableRow): string
    {
        $buttons = '';
        $isRequestable = $tableRow['is_requestable'] ?? false;
        $hasDownloadableRequest = $tableRow['has_downloadable_request'] ?? false;
        if ($isRequestable) {
            $buttons .= '<a href="#" class="btn-request-send"><i class="icon">mail</i></a>';
        }
        if ($hasDownloadableRequest) {
            $buttons .= '<a href="#" class="btn-request-download"><i class="icon">download</i></a>';
        }
        if (strlen($buttons) === 0) {
            return '';
        }

        return "<div class='d-flex flex-row gap-2'>{$buttons}</div>";
    }

    protected function isAllowed(string $permission): bool
    {
        return $this->prototype->hasPermission($permission) && Acl::isAllowed($this->prototype->key, $permission);
    }

    protected function getPrototypeKey(): string
    {
        return 'transfer-booking';
    }
}
