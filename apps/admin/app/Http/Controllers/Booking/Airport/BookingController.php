<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Airport;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Client\Client;
use App\Admin\Repositories\BookingAdministratorRepository;
use App\Admin\Support\Facades\Booking\AirportAdapter;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Booking\StatusAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingAdministratorRepository $administratorRepository
    ) {
    }

    public function index(): LayoutContract
    {
        Breadcrumb::add('Бронирование');
        Breadcrumb::add('Брони услуг аэропорта');

        $statuses = StatusAdapter::getStatuses();
        $grid = $this->gridFactory($statuses);
//        $query = $this->repository->queryWithCriteria($grid->getSearchCriteria());
//        $this->prepareGridQuery($query);
        $data = AirportAdapter::getBookings();
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
        Breadcrumb::add('Бронирование');
        Breadcrumb::add('Новая бронь');

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
        $creator = request()->user();
        $bookingId = AirportAdapter::createBooking(
            cityId: $data['city_id'],
            clientId: $data['client_id'],
            airportId: $data['airport_id'],
            serviceId: $data['service_id'],
            date: new Carbon($data['date'] . ' ' . $data['time']),
            creatorId: $creator->id,
            orderId: $data['order_id'] ?? null,
            note: $data['note'] ?? null
        );
        $this->administratorRepository->create($bookingId, $creator->id);

        return redirect(
            route('airport-booking.show', $bookingId)
        );
    }

    public function show(int $id): LayoutContract
    {
        $title = "Бронь №{$id}";

        $booking = AirportAdapter::getBooking($id);
        $order = OrderAdapter::findOrder($booking->orderId);
        $client = Client::find($order->clientId);

//        Breadcrumb::prototype($this->prototype)
//            ->add($title);

//        $this->prepareShowMenu($this->model);

        return Layout::title($title)
            ->view('airport-booking.show.show', [
                'bookingId' => $id,
                'model' => $booking,
                'client' => $client,
                'order' => $order,
                'manager' => $this->administratorRepository->get($id),
                'creator' => Administrator::find($booking->creatorId),
//                'editUrl' => $this->isAllowed('update') ? $this->route('edit', $id) : null,
//                'deleteUrl' => $this->isAllowed('delete') ? $this->route('destroy', $id) : null,
                'editUrl' => null,
                'deleteUrl' => null,
            ]);
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('order_id', [
                'label' => 'Заказ',
                'emptyItem' => 'Создать новый заказ',
                'items' => OrderAdapter::getActiveOrders()
            ])
            ->hidden('client_id', [
                'label' => __('label.client'),
                'required' => true,
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
//            ->hidden('legal_id', [
//                'label' => 'Юр. лицо',
//            ])
            ->hidden('airport_id', [
                'label' => 'Аэропорт',
                'required' => true,
            ])
            ->date('date', [
                'label' => 'Дата прилёта/вылета',
                'required' => true
            ])
            ->time('time', [
                'label' => 'Время',
                'required' => true
            ])

//            ->addElement('manager_id', 'select', [
//                'label' => 'Менеджер',
//                'default' => App::getUserId(),
//                'textIndex' => 'presentation',
//                'items' => $managers
//            ])
            ->textarea('note', ['label' => 'Примечание']);
    }

    protected function gridFactory(array $statuses = []): GridContract
    {
        return Grid::enableQuicksearch()
            ->id('id', ['text' => '№', 'route' => fn($r) => route('airport-booking.show', $r), 'order' => true])
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => $statuses])
            ->text('client_name', ['text' => 'Клиент'])
            ->text('city_name', ['text' => 'Город'])
            ->text('service_name', ['text' => 'Название услуги'])
//            ->date('date', ['text' => 'Дата прилёта/вылета'])
            ->text('flight_number', ['text' => 'Номер рейса'])
            ->number('tourist_count', ['text' => 'Кол-во туристов'])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->paginator(20);
    }
}
