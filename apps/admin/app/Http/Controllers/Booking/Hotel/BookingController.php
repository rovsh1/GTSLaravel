<?php

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Booking\UpdateExternalNumberRequest;
use App\Admin\Http\Requests\Booking\UpdatePriceRequest;
use App\Admin\Http\Requests\Booking\UpdateStatusRequest;
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
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
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

        $statuses = StatusAdapter::getStatuses();
        $grid = $this->gridFactory($statuses);
        $query = HotelAdapter::getBookingQuery()->applyCriteria($grid->getSearchCriteria());
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
                'cancelUrl' => $this->prototype->route('index')
            ]);
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post');

        $form->trySubmit($this->prototype->route('create'));

        $data = $form->getData();
        $creator = request()->user();
        $bookingId = HotelAdapter::createBooking(
            cityId: $data['city_id'],
            clientId: $data['client_id'],
            legalId: $data['legal_id'],
            currencyId: $data['currency_id'],
            hotelId: $data['hotel_id'],
            period: $data['period'],
            creatorId: $creator->id,
            orderId: $data['order_id'] ?? null,
            note: $data['note'] ?? null
        );
        $this->administratorRepository->create($bookingId, $creator->id);

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

        $form = $this->formFactory()
            ->method('put')
            ->action($this->prototype->route('update', $id))
            ->data($booking);

        return Layout::title($title)
            ->view($this->prototype->view('edit') ?? $this->prototype->view('form') ?? 'default.form.form', [
                'model' => $booking,
                'form' => $form,
                'cancelUrl' => $this->prototype->route('show', $id),
                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null
            ]);
    }

    public function update(int $id): RedirectResponse
    {
        //@todo при обновлении дат, не забудь обновить CancelConditions
        throw new \Exception('Not implemented yet.');
        $this->model = $this->repository->findOrFail($id);

        $form = $this->formFactory()
            ->method('put');

        $form->trySubmit($this->prototype->route('edit', $this->model));

        $preparedData = $this->saving($form->getData());
        $this->repository->update($this->model->id, $preparedData);

        $redirectUrl = $this->prototype->route('index');
        if ($this->hasShowAction()) {
            $redirectUrl = $this->prototype->route('show', $this->model);
        }

        return redirect($redirectUrl);
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

    protected function gridFactory(array $statuses = []): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->id('id', ['text' => '№', 'route' => $this->prototype->routeName('show'), 'order' => true])
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => $statuses])
            ->text('client_name', ['text' => 'Клиент'])
            ->date('date_start', ['text' => 'Дата заезда'])
            ->date('date_end', ['text' => 'Дата выезда'])
            ->text('city_name', ['text' => 'Город'])
            ->text('hotel_name', ['text' => 'Отель'])
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

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('order_id', [
                'label' => 'Заказ',
                'emptyItem' => 'Создать новый заказ',
                'items' => OrderAdapter::getActiveOrders()
            ])
            ->city('city_id', [
                'label' => __('label.city'),
                'emptyItem' => '',
                'required' => true,
                'onlyWithHotels' => true
            ])
            ->hidden('client_id', [
                'label' => __('label.client'),
                'required' => true,
            ])
            ->hidden('legal_id', [
                'label' => 'Юр. лицо',
            ])
            ->currency('currency_id', [
                'label' => 'Валюта',
                'required' => true,
                'value' => 1
            ])
            ->hotel('hotel_id', [
                'label' => 'Отель',
                'required' => true,
                'emptyItem' => '',
                //'disabled' => true, @todo при редактировании - disabled
            ])
            ->dateRange('period', [
                'label' => 'Дата заезда/выезда',
                'required' => true
            ])

//            ->addElement('manager_id', 'select', [
//                'label' => 'Менеджер',
//                'default' => App::getUserId(),
//                'textIndex' => 'presentation',
//                'items' => $managers
//            ])
//            ->addElement('period', 'daterange', ['label' => 'Дата заезда/выезда', 'required' => true,])
            //->addElement('date_checkin', 'date', ['required' => true, 'label' => 'Дата заезда'])
            //->addElement('date_checkout', 'date', ['required' => true, 'label' => 'Дата выезда'])
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
