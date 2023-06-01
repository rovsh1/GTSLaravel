<?php

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Http\Requests\Booking\UpdateExternalNumberRequest;
use App\Admin\Http\Requests\Booking\UpdateStatusRequest;
use App\Admin\Http\Resources\Room as RoomResource;
use App\Admin\Models\Booking\Administrator;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Room;
use App\Admin\Support\Facades\Booking\HotelAdapter;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Booking\StatusAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class BookingController extends AbstractPrototypeController
{
    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $grid = $this->gridFactory();
//        $query = $this->repository->queryWithCriteria($grid->getSearchCriteria());
//        $this->prepareGridQuery($query);
        $data = HotelAdapter::getBookings();
        $grid->data($data);

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
            hotelId: $data['hotel_id'],
            period: $data['period'],
            creatorId: $creator->id,
            orderId: $data['order_id'] ?? null,
            note: $data['note'] ?? null
        );
        Administrator::create([
            'booking_id' => $bookingId,
            'administrator_id' => $creator->id
        ]);

        return redirect(
            $this->prototype->route('show', $bookingId)
        );
    }

    public function show(int $id): LayoutContract
    {
        $title = "Бронь №{$id}";

        $order = OrderAdapter::findOrder($id);
        $booking = HotelAdapter::getBooking($id);
        $details = HotelAdapter::getBookingDetails($id);
        $hotelId = $details->hotelId;
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
                'details' => $details,
                'client' => $client,
                'order' => $order,
                'editUrl' => $this->isAllowed('update') ? $this->route('edit', $id) : null,
                'deleteUrl' => $this->isAllowed('delete') ? $this->route('destroy', $id) : null,
                'hotelRooms' => RoomResource::collection(Room::whereHotelId($hotelId)->get())
            ]);
    }

    public function edit(int $id): LayoutContract
    {
        $breadcrumbs = Breadcrumb::prototype($this->prototype);

        $this->model = HotelAdapter::getBooking($id);

        $title = "Бронь №{$id}";
        $breadcrumbs->addUrl($this->prototype->route('show', $id), $title);
        $breadcrumbs->add($this->prototype->title('edit') ?? 'Редактирование');

        $form = $this->formFactory()
            ->method('put')
            ->action($this->prototype->route('update', $id))
            ->data($this->model);

        return Layout::title($title)
            ->view($this->prototype->view('edit') ?? $this->prototype->view('form') ?? 'default.form.form', [
                'model' => $this->model,
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

    public function getDetails(int $id): JsonResponse
    {
        return response()->json(
            HotelAdapter::getBookingDetails($id)
        );
    }

    public function getStatuses(): JsonResponse
    {
        return response()->json(
            StatusAdapter::getStatuses()
        );
    }

    public function getAvailableStatuses(int $id): JsonResponse
    {
        return response()->json(
            StatusAdapter::getAvailableStatuses($id)
        );
    }

    public function updateStatus(UpdateStatusRequest $request, int $id): AjaxResponseInterface
    {
        StatusAdapter::updateStatus($id, $request->getStatus());
        return new AjaxSuccessResponse();
    }

    public function updateExternalNumber(UpdateExternalNumberRequest $request, int $id): AjaxResponseInterface
    {
        HotelAdapter::updateExternalNumber($id, $request->getType(), $request->getNumber());
        return new AjaxSuccessResponse();
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->id('id', ['text' => '№', 'route' => $this->prototype->routeName('show'), 'order' => true])
            ->text('status', ['text' => 'Статус', 'renderer' => fn($v, $t) => $t->name, 'order' => true])
            ->text('client_name', ['text' => 'Клиент'])
            ->date('date_start', ['text' => 'Дата заезда'])
            ->date('date_end', ['text' => 'Дата выезда'])
            ->text('city_name', ['text' => 'Город'])
            ->text('hotel_name', ['text' => 'Отель'])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->paginator(20);
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
                'required' => true
            ])
            ->hidden('client_id', [
                'label' => __('label.client'),
                'required' => true,
            ])
//            ->hidden('legal_id', [
//                'label' => 'Юр. лицо',
//            ])
            ->hotel('hotel_id', [
                'label' => 'Отель',
                'required' => true,
                'emptyItem' => ''
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

    protected function route(string $name, mixed $parameters = []): string
    {
        return route("hotel-booking.{$name}", $parameters);
    }
}
