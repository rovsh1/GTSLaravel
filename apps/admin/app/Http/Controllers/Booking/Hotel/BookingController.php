<?php

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Booking\BulkDeleteRequest;
use App\Admin\Http\Requests\Booking\UpdateExternalNumberRequest;
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

        $grid = $this->gridFactory();
        $query = HotelAdapter::getBookingQuery()
            ->applyCriteria($grid->getSearchCriteria())
            ->join('administrator_bookings', 'administrator_bookings.booking_id', '=', 'bookings.id')
            ->join('administrators', 'administrators.id', '=', 'administrator_bookings.administrator_id')
            ->addSelect('administrators.presentation as manager_name')
            ->addSelect(
                \DB::raw(
                    '(SELECT SUM(guests_count) FROM booking_hotel_rooms WHERE booking_id=bookings.id) as guests_count'
                )
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
                'clients' => ClientResource::collection(Client::orderBy('name')->get()),
                'cancelUrl' => $this->prototype->route('index'),
                'createClientUrl' => route('client.dialog.create'),
            ]);
    }

    public function store(): RedirectResponse
    {
        $creatorId = request()->user()->id;

        $form = $this->formFactory()->method('post');
        $form->trySubmit($this->prototype->route('create'));

        $data = $form->getData();
        //@todo добавить селект типа брони По квоте/По запросу (списывать квоту сразу, если по квоте)
        $bookingId = HotelAdapter::createBooking(
            cityId: $data['city_id'],
            clientId: $data['client_id'],
            legalId: $data['legal_id'],
            currencyId: $data['currency_id'],
            hotelId: $data['hotel_id'],
            period: $data['period'],
            creatorId: $creatorId,
            orderId: $data['order_id'] ?? null,
            note: $data['note'] ?? null
        );
        $this->administratorRepository->create($bookingId, $data['manager_id'] ?? $creatorId);

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
            ->view($this->getPrototypeKey() . '.show.show', [
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
                'clients' => ClientResource::collection(Client::orderBy('name')->get()),
                'createClientUrl' => route('client.dialog.create'),
                'cancelUrl' => $this->prototype->route('show', $id),
//                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null
            ]);
    }

    public function update(int $id): RedirectResponse
    {
        $form = $this->formFactory(true)->method('put');

        $form->trySubmit($this->prototype->route('edit', $id));

        $data = $form->getData();
        HotelAdapter::updateBooking(
            id: $id,
            clientId: $data['client_id'],
            legalId: $data['legal_id'],
            currencyId: $data['currency_id'],
            period: $data['period'],
            note: $data['note'] ?? null
        );
        $this->administratorRepository->update($id, $data['manager_id'] ?? request()->user()->id);

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

        return new AjaxSuccessResponse();
    }

    public function bulkDelete(BulkDeleteRequest $request): AjaxResponseInterface
    {
        HotelAdapter::bulkDeleteBookings($request->getIds());

        return new AjaxSuccessResponse();
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->checkbox('checked', ['checkboxClass' => 'js-select-booking', 'dataAttributeName' => 'booking-id'])
            ->id('id', ['text' => '№', 'route' => $this->prototype->routeName('show'), 'order' => true])
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => StatusAdapter::getStatuses(), 'order' => true])
            ->text('client_name', ['text' => 'Клиент'])
            ->text('manager_name', ['text' => 'Менеджер'])
            ->date('date_start', ['text' => 'Дата заезда'])
            ->date('date_end', ['text' => 'Дата выезда'])
            ->text('city_name', ['text' => 'Город'])
            ->text('hotel_name', ['text' => 'Отель'])
            ->text('guests_count', ['text' => 'Гостей'])
            ->text('source', ['text' => 'Источник', 'order' => true])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->paginator(20);
    }

    private function searchForm()
    {
        return (new SearchForm())
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
            ->select('client_id', [
                'label' => __('label.client'),
                'required' => true,
                'emptyItem' => '',
                'items' => Client::orderBy('name')->get(),
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
                'required' => true,
                'value' => 1
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
