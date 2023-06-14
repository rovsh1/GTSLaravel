<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Airport;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Booking\AirportAdapter;
use App\Admin\Support\Facades\Booking\StatusAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;

class BookingController extends Controller
{
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

    protected function gridFactory(array $statuses = []): GridContract
    {
        return Grid::enableQuicksearch()
            ->id('id', ['text' => '№', 'route' => fn($r) => route('airport-booking.show', $r), 'order' => true])
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => $statuses])
            ->text('client_name', ['text' => 'Клиент'])
            ->text('city_name', ['text' => 'Город'])
            ->text('service_name', ['text' => 'Название услуги'])
            ->date('date_start', ['text' => 'Дата прилёта/вылета'])
            ->text('flight_number', ['text' => 'Номер рейса'])
            ->number('tourist_count', ['text' => 'Кол-во туристов'])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->paginator(20);
    }
}
