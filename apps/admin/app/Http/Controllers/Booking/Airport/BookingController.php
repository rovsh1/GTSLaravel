<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Airport;

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
use App\Admin\Models\Supplier\AirportService;
use App\Admin\Repositories\BookingAdministratorRepository;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Booking\Airport\PriceAdapter;
use App\Admin\Support\Facades\Booking\AirportAdapter;
use App\Admin\Support\Facades\Booking\HotelAdapter;
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
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
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
        $data = AirportAdapter::getBookingQuery()
            ->applyCriteria($grid->getSearchCriteria())
            ->join('administrator_bookings', 'administrator_bookings.booking_id', '=', 'bookings.id')
            ->join('administrators', 'administrators.id', '=', 'administrator_bookings.administrator_id')
            ->addSelect('administrators.presentation as manager_name')
            ->selectSub(
                DB::table('booking_airport_guests')
                    ->selectRaw('count(id)')
                    ->whereColumn('booking_airport_guests.booking_airport_id', 'bookings.id'),
                'guests_count'
            )
            ->addSelect(
                DB::raw('(SELECT bookings.status IN (' . implode(',', $requestableStatuses) . ')) as is_requestable'),
            )
            ->addSelect(
                DB::raw(
                    'EXISTS(SELECT 1 FROM booking_requests WHERE bookings.id = booking_requests.booking_id AND is_archive = 0) as has_downloadable_request'
                ),
            );

        $grid->data($data);

        return Layout::title('Брони услуг аэропорта')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator(),
                'createUrl' => route('airport-booking.create')
            ]);
    }

    public function create(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype)
            ->add($this->prototype->title('create') ?? 'Новая запись');

        $form = $this->formFactory()
            ->method('post')
            ->action(route('airport-booking.store'));

        return Layout::title('Новая бронь')
            ->view('airport-booking.form.form', [
                'form' => $form,
                'cancelUrl' => route('airport-booking.index')
            ]);
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post');

        $form->trySubmit(route('airport-booking.store'));

        $data = $form->getData();
        $creatorId = request()->user()->id;
        $orderId = $data['order_id'] ?? null;
        $currencyId = $data['currency_id'] ?? null;
        if ($orderId !== null && $currencyId === null) {
            $order = OrderAdapter::findOrder($orderId);
            $currencyId = $order->currency->id;
        }
        $client = Client::find($data['client_id']);
        $bookingId = AirportAdapter::createBooking(
            cityId: $data['city_id'],
            clientId: $data['client_id'],
            legalId: $data['legal_id'],
            currencyId: $currencyId ?? $client->currency_id,
            airportId: $data['airport_id'],
            serviceId: $data['service_id'],
            flightNumber: $data['flight_number'],
            date: new Carbon($data['date'] . ' ' . $data['time']),
            creatorId: $creatorId,
            orderId: $data['order_id'] ?? null,
            note: $data['note'] ?? null
        );
        $this->administratorRepository->create($bookingId, $creatorId);

        return redirect(
            route('airport-booking.show', $bookingId)
        );
    }

    public function edit(int $id): LayoutContract
    {
        $breadcrumbs = Breadcrumb::prototype($this->prototype);

        $booking = HotelAdapter::getBooking($id);

        $title = "Бронь №{$id}";
        $breadcrumbs->addUrl($this->prototype->route('show', $id), $title);
        $breadcrumbs->add($this->prototype->title('edit') ?? 'Редактирование');

        $form = $this->formFactory(true)
            ->method('put')
            ->action($this->prototype->route('update', $id))
            ->data($this->prepareFormData($booking));

        return Layout::title($title)
            ->view($this->prototype->view('edit') ?? $this->prototype->view('form') ?? 'default.form.form', [
                'model' => $booking,
                'form' => $form,
                'cancelUrl' => $this->prototype->route('show', $id),
                'editUrl' => $this->isAllowed('update') ? $this->prototype->route('edit', $id) : null,
                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null,
            ]);
    }

    public function show(int $id): LayoutContract
    {
        $title = "Бронь №{$id}";

        $booking = AirportAdapter::getBooking($id);
        $order = OrderAdapter::findOrder($booking->orderId);
        $client = Client::find($order->clientId);

        Breadcrumb::prototype($this->prototype)
            ->add($title);

        return Layout::title($title)
            ->view('airport-booking.show.show', [
                'bookingId' => $id,
                'model' => $booking,
                'client' => $client,
                'order' => $order,
                'currencies' => Currency::get(),
                'manager' => $this->administratorRepository->get($id),
                'creator' => Administrator::find($booking->creatorId),
//                'editUrl' => $this->isAllowed('update') ? $this->route('edit', $id) : null,
//                'deleteUrl' => $this->isAllowed('delete') ? $this->route('destroy', $id) : null,
                'editUrl' => null,
                'deleteUrl' => null,
            ]);
    }

    public function get(int $id): JsonResponse
    {
        return response()->json(
            AirportAdapter::getBooking($id)
        );
    }

    public function getAvailableActions(int $id): JsonResponse
    {
        return response()->json(
            AirportAdapter::getAvailableActions($id)
        );
    }

    public function getStatuses(): JsonResponse
    {
        return response()->json(
            AirportAdapter::getStatuses()
        );
    }

    public function updateStatus(UpdateStatusRequest $request, int $id): JsonResponse
    {
        return response()->json(
            AirportAdapter::updateStatus(
                $id,
                $request->getStatus(),
                $request->getNotConfirmedReason(),
                $request->getCancelFeeAmount()
            )
        );
    }

    public function getStatusHistory(int $id): JsonResponse
    {
        return response()->json(
            AirportAdapter::getStatusHistory($id)
        );
    }

    public function bulkDelete(BulkDeleteRequest $request): AjaxResponseInterface
    {
        AirportAdapter::bulkDeleteBookings($request->getIds());

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
        AirportAdapter::updateNote($id, $request->getNote());

        return new AjaxSuccessResponse();
    }

    public function updateManager(int $id, UpdateManagerRequest $request): AjaxResponseInterface
    {
        $this->administratorRepository->update($id, $request->getManagerId());

        return new AjaxSuccessResponse();
    }

    public function copy(int $id): RedirectResponse
    {
        $newBookingId = AirportAdapter::copyBooking($id);

        $administrator = $this->administratorRepository->get($id);
        $this->administratorRepository->create($newBookingId, $administrator->id);

        return redirect(
            route('airport-booking.show', $newBookingId)
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
                'onlyWithAirports' => true
            ])
            ->hidden('service_id', [
                'label' => 'Услуга',
                'required' => true,
            ])
            ->hidden('airport_id', [
                'label' => 'Аэропорт',
                'required' => true,
            ])
            ->manager('manager_id', [
                'label' => 'Менеджер',
                'emptyItem' => '',
                'value' => request()->user()->id,
            ])
            ->text('flight_number', ['label' => 'Номер рейса', 'required' => true])
            ->date('date', [
                'label' => 'Дата прилёта/вылета',
                'required' => true
            ])
            ->time('time', [
                'label' => 'Время',
                'required' => true
            ])
            ->textarea('note', ['label' => 'Примечание']);
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
                    $bookingUrl = route('airport-booking.show', $row['id']);
                    $idLink = "<a href='{$bookingUrl}'>{$row['id']}</a>";
                    $orderId = $row['order_id'];
                    $orderUrl = route('booking-order.show', $orderId);
                    $orderLink = "<a href='{$orderUrl}'>{$orderId}</a>";

                    return "$idLink / {$orderLink}";
                }
            ])
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => AirportAdapter::getStatuses(), 'order' => true]
            )
            ->text('client_name', ['text' => 'Клиент'])
            ->text('city_name', ['text' => 'Город'])
            ->text('service_name', ['text' => 'Название услуги'])
            ->date('date', ['text' => 'Дата прилёта/вылета'])
            ->text('flight_number', ['text' => 'Номер рейса'])
            ->text('guests_count', ['text' => 'Гостей'])
            ->text('source', ['text' => 'Источник', 'order' => true])
            ->text('actions', ['renderer' => fn($row, $val) => $this->getActionButtons($row)])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->orderBy('created_at', 'desc')
            ->paginator(20);
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->number('order_id', ['label' => '№ Заказа'])
            ->country('country_id', ['label' => 'Страна', 'default' => '1'])
            ->city('city_id', ['label' => 'Город', 'emptyItem' => '', 'onlyWithAirports' => true])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => ''])
            ->numRange('guests_count', ['label' => 'Кол-во гостей'])
            ->select('service_id', ['label' => 'Услуга', 'emptyItem' => '', 'items' => AirportService::get()])
            ->select('manager_id', ['label' => 'Менеджер', 'items' => Administrator::all(), 'emptyItem' => ''])
            ->select('status', ['label' => 'Статус', 'items' => HotelAdapter::getStatuses(), 'emptyItem' => ''])
            ->enum('source', ['label' => 'Источник', 'enum' => SourceEnum::class, 'emptyItem' => ''])
            ->dateRange('date_period', ['label' => 'Дата прилета/вылета'])
            ->dateRange('created_period', ['label' => 'Дата создания']);
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
        return 'airport-booking';
    }
}
