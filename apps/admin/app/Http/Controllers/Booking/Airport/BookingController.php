<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Airport;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Reference\Currency;
use App\Admin\Repositories\BookingAdministratorRepository;
use App\Admin\Support\Facades\Booking\AirportAdapter;
use App\Admin\Support\Facades\Booking\HotelAdapter;
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
use Illuminate\Http\JsonResponse;
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

        $grid = $this->gridFactory();
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
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => StatusAdapter::getStatuses(), 'order' => true])
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
