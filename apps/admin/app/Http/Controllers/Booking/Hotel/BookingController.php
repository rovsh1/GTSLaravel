<?php

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Booking\BulkDeleteRequest;
use App\Admin\Http\Requests\Booking\UpdateExternalNumberRequest;
use App\Admin\Http\Requests\Booking\UpdateManagerRequest;
use App\Admin\Http\Requests\Booking\UpdateNoteRequest;
use App\Admin\Http\Requests\Booking\UpdatePriceRequest;
use App\Admin\Http\Requests\Booking\UpdateStatusRequest;
use App\Admin\Http\Resources\Client as ClientResource;
use App\Admin\Http\Resources\Room as RoomResource;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Room;
use App\Admin\Models\Reference\Currency;
use App\Admin\Repositories\BookingAdministratorRepository;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Booking\BookingAdapter;
use App\Admin\Support\Facades\Booking\HotelAdapter;
use App\Admin\Support\Facades\Booking\HotelPriceAdapter;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Booking\StatusAdapter;
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
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Shared\Application\Exception\ApplicationException;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
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
        $query = HotelAdapter::getBookingQuery()
            ->applyCriteria($grid->getSearchCriteria())
            ->join('administrator_bookings', 'administrator_bookings.booking_id', '=', 'bookings.id')
            ->join('administrators', 'administrators.id', '=', 'administrator_bookings.administrator_id')
            ->addSelect('administrators.presentation as manager_name')
            ->addSelect(
                DB::raw(
                    '(SELECT SUM(guests_count) FROM booking_hotel_rooms WHERE booking_id=bookings.id) as guests_count'
                )
            )
            ->addSelect(
                DB::raw(
                    '(SELECT GROUP_CONCAT(room_name) FROM booking_hotel_rooms WHERE booking_id=bookings.id) as room_names'
                )
            )
            ->addSelect(
                DB::raw('(SELECT COUNT(id) FROM booking_hotel_rooms WHERE booking_id=bookings.id) as rooms_count')
            )
            ->addSelect(
                DB::raw('(SELECT bookings.status IN (' . implode(',', $requestableStatuses) . ')) as is_requestable'),
            )
            ->addSelect(
                DB::raw(
                    'EXISTS(SELECT 1 FROM booking_requests WHERE bookings.id = booking_requests.booking_id AND is_archive = 0) as has_downloadable_request'
                ),
            );

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
            ->add($this->prototype->title('create') ?? 'Новая запись');

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
        $creatorId = request()->user()->id;

        $form = $this->formFactory()
            ->method('post')
            ->failUrl($this->prototype->route('create'));

        $form->trySubmit();

        $data = $form->getData();
        $orderId = $data['order_id'] ?? null;
        $currencyId = $data['currency_id'] ?? null;
        if ($orderId !== null && $currencyId === null) {
            $order = OrderAdapter::findOrder($orderId);
            $currencyId = $order->currency->id;
        }
        $client = Client::find($data['client_id']);
        try {
            $bookingId = HotelAdapter::createBooking(
                cityId: $data['city_id'],
                clientId: $data['client_id'],
                legalId: $data['legal_id'],
                currencyId: $currencyId ?? $client->currency_id,
                hotelId: $data['hotel_id'],
                period: $data['period'],
                creatorId: $creatorId,
                orderId: $data['order_id'] ?? null,
                note: $data['note'] ?? null,
                quotaProcessingMethod: $data['quota_processing_method'],
            );
            $this->administratorRepository->create($bookingId, $data['manager_id'] ?? $creatorId);
        } catch (ApplicationException $e) {
            $form->throwException($e);
        }

        return redirect(
            $this->prototype->route('show', $bookingId)
        );
    }

    public function show(int $id): LayoutContract
    {
        $title = "Бронь №{$id}";

        $booking = HotelAdapter::getBooking($id);
        $order = OrderAdapter::findOrder($booking->orderId);
        $hotelId = $booking->hotelInfo->id;
        $client = Client::find($order->clientId);
        $hotel = Hotel::find($hotelId);

//        Breadcrumb::prototype($this->prototype)
//            ->add($title);

//        $this->prepareShowMenu($this->model);

        return Layout::title($title)
            ->view($this->prototype->view('show'), [
                'bookingId' => $id,
                'hotelId' => $hotelId,
                'hotel' => $hotel,
                'model' => $booking,
                'client' => $client,
                'order' => $order,
                'currencies' => Currency::get(),
                'manager' => $this->administratorRepository->get($id),
                'creator' => Administrator::find($booking->creatorId),
                'editUrl' => $this->isAllowed('update') ? $this->route('edit', $id) : null,
                'deleteUrl' => $this->isAllowed('delete') ? $this->route('destroy', $id) : null,
                'hotelRooms' => RoomResource::collection(Room::whereHotelId($hotelId)->get())
            ]);
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
//                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null
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
            HotelAdapter::updateBooking(
                id: $id,
                period: $data['period'],
                quotaProcessingMethod: $data['quota_processing_method'],
                note: $data['note'] ?? null
            );
            $this->administratorRepository->update($id, $data['manager_id'] ?? request()->user()->id);
        } catch (ApplicationException $e) {
            $form->throwException($e);
        }

        return redirect($this->prototype->route('show', $id));
    }

    public function destroy(int $id): AjaxResponseInterface
    {
        HotelAdapter::deleteBooking($id);

        return new AjaxRedirectResponse($this->prototype->route());
    }

    public function get(int $id): JsonResponse
    {
        return response()->json(
            HotelAdapter::getBooking($id)
        );
    }

    public function copy(int $id): RedirectResponse
    {
        $newBookingId = HotelAdapter::copyBooking($id);

        $administrator = $this->administratorRepository->get($id);
        $this->administratorRepository->create($newBookingId, $administrator->id);

        return redirect(
            $this->prototype->route('show', $newBookingId)
        );
    }

    public function getStatuses(): JsonResponse
    {
        return response()->json(
            StatusAdapter::getStatuses()
        );
    }

    public function getAvailableActions(int $id): JsonResponse
    {
        return response()->json(
            BookingAdapter::getAvailableActions($id)
        );
    }

    public function updateStatus(UpdateStatusRequest $request, int $id): JsonResponse
    {
        return response()->json(
            StatusAdapter::updateStatus(
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
            StatusAdapter::getStatusHistory($id)
        );
    }

    public function updateExternalNumber(UpdateExternalNumberRequest $request, int $id): AjaxResponseInterface
    {
        HotelAdapter::updateExternalNumber($id, $request->getType(), $request->getNumber());

        return new AjaxSuccessResponse();
    }

    public function updatePrice(UpdatePriceRequest $request, int $id): AjaxResponseInterface
    {
        $boPrice = $request->getBoPrice();
        $hoPrice = $request->getHoPrice();

        if ($request->isBoPriceExists() && $boPrice === null) {
            HotelPriceAdapter::setCalculatedBoPrice($id);
        }
        if ($request->isHoPriceExists() && $hoPrice === null) {
            HotelPriceAdapter::setCalculatedHoPrice($id);
        }

        if ($boPrice !== null) {
            HotelPriceAdapter::setBoPrice($id, $boPrice);
        }
        if ($hoPrice !== null) {
            HotelPriceAdapter::setHoPrice($id, $hoPrice);
        }
        if ($request->isBoPenaltyExists()) {
            HotelPriceAdapter::setBoPenalty($id, $request->getBoPenalty());
        }
        if ($request->isHoPenaltyExists()) {
            HotelPriceAdapter::setHoPenalty($id, $request->getHoPenalty());
        }

        return new AjaxSuccessResponse();
    }

    public function bulkDelete(BulkDeleteRequest $request): AjaxResponseInterface
    {
        HotelAdapter::bulkDeleteBookings($request->getIds());

        return new AjaxSuccessResponse();
    }

    public function updateNote(int $id, UpdateNoteRequest $request): AjaxResponseInterface
    {
        HotelAdapter::updateNote($id, $request->getNote());

        return new AjaxSuccessResponse();
    }

    public function updateManager(int $id, UpdateManagerRequest $request): AjaxResponseInterface
    {
        $this->administratorRepository->update($id, $request->getManagerId());

        return new AjaxSuccessResponse();
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
                    $bookingUrl = route($this->prototype->routeName('show'), $row['id']);
                    $idLink = "<a href='{$bookingUrl}'>{$row['id']}</a>";
                    $orderId = $row['order_id'];
                    $orderUrl = route('booking-order.show', $orderId);
                    $orderLink = "<a href='{$orderUrl}'>{$orderId}</a>";

                    return "$idLink / {$orderLink}";
                }
            ])
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => StatusAdapter::getStatuses(), 'order' => true])
            ->text('client_name', ['text' => 'Клиент'])
            ->text('manager_name', ['text' => 'Менеджер'])
            ->text(
                'date_start',
                [
                    'text' => 'Заезд - выезд',
                    'renderer' => fn($row, $val) => \Format::period(new CarbonPeriod($val, $row['date_end']))
                ]
            )
            ->text(
                'city_name',
                ['text' => 'Город / Отель', 'renderer' => fn($row, $val) => "{$val} / {$row['hotel_name']}"]
            )
            ->textWithTooltip(
                'rooms_count',
                ['text' => 'Номера', 'tooltip' => fn($row, $val) => $this->getRoomNamesTooltip($row)]
            )
            ->text('guests_count', ['text' => 'Гостей'])
            ->text('source', ['text' => 'Источник', 'order' => true])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->text('actions', ['renderer' => fn($row, $val) => $this->getActionButtons($row)])
            ->orderBy('created_at', 'desc')
            ->paginator(20);
    }


    private function getRoomNamesTooltip(mixed $tableRow): string
    {
        $roomNames = $tableRow['room_names'] ?? null;
        if ($roomNames === null) {
            return '';
        }
        $roomNames = explode(',', $roomNames);

        return collect($roomNames)
            ->groupBy(fn(string $val) => trim($val))
            ->map(fn(Collection $values, $key) => "{$values->first()}: {$values->count()}")
            ->implode('<br>');
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

    private function searchForm()
    {
        return (new SearchForm())
            ->number('order_id', ['label' => '№ Заказа'])
            ->country('country_id', ['label' => 'Страна', 'default' => '1'])
            ->city('city_id', ['label' => 'Город', 'emptyItem' => '', 'onlyWithHotels' => true])
            ->hidden('hotel_id', ['label' => 'Отель'])
            ->hidden('hotel_room_id', ['label' => 'Тип номера'])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => ''])
            ->select('manager_id', ['label' => 'Менеджер', 'items' => Administrator::all(), 'emptyItem' => ''])
            ->select('status', ['label' => 'Статус', 'items' => StatusAdapter::getStatuses(), 'emptyItem' => ''])
            ->enum('source', ['label' => 'Источник', 'enum' => SourceEnum::class, 'emptyItem' => ''])
            ->numRange('guests_count', ['label' => 'Кол-во гостей'])
            ->dateRange('start_period', ['label' => 'Дата заезда'])
            ->dateRange('end_period', ['label' => 'Дата выезда'])
            ->dateRange('created_period', ['label' => 'Дата создания']);
    }

    private function prepareFormData(object $booking): array
    {
        $hotelId = $booking->hotelInfo->id;
        $cityId = Hotel::find($hotelId)->city_id;
        $order = OrderAdapter::findOrder($booking->orderId);
        $manager = $this->administratorRepository->get($booking->id);

        return [
            'quota_processing_method' => $booking->quotaProcessingMethod->value,
            'manager_id' => $manager->id,
            'order_id' => $booking->orderId,
            'currency_id' => $order->currency->id,
            'hotel_id' => $hotelId,
            'city_id' => $cityId,
            'client_id' => $order->clientId,
            'legal_id' => $order->legalId,
            'period' => new CarbonPeriod($booking->period->dateFrom, $booking->period->dateTo),
            'note' => $booking->note,
        ];
    }

    protected function formFactory(bool $isEdit = false): FormContract
    {
        return Form::name('data')
            ->radio('quota_processing_method', [
                'label' => 'Тип брони',
                'emptyItem' => '',
                'required' => true,
                'items' => [
                    ['id' => QuotaProcessingMethodEnum::REQUEST->value, 'name' => 'По запросу'],
                    ['id' => QuotaProcessingMethodEnum::QUOTE->value, 'name' => 'По квоте'],
                ]
            ])
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
                'required' => !$isEdit,
                'onlyWithHotels' => true,
                'disabled' => $isEdit
            ])
            ->hidden('hotel_id', [
                'label' => 'Отель',
                'required' => !$isEdit,
                'emptyItem' => '',
                'disabled' => $isEdit,
            ])
            ->manager('manager_id', [
                'label' => 'Менеджер',
                'emptyItem' => '',
                'value' => request()->user()->id,
            ])
            ->dateRange('period', [
                'label' => 'Дата заезда/выезда',
                'required' => true
            ])
            ->textarea('note', ['label' => 'Примечание']);
    }

    protected function getPrototypeKey(): string
    {
        return 'hotel-booking';
    }

    protected function isAllowed(string $permission): bool
    {
        return $this->prototype->hasPermission($permission) && Acl::isAllowed($this->prototype->key, $permission);
    }

    protected function route(string $name, mixed $parameters = []): string
    {
        return route("hotel-booking.{$name}", $parameters);
    }
}
