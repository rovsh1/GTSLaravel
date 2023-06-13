<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Airport;

use App\Admin\Support\Facades\Booking\AirportAdapter;
use App\Admin\Support\Facades\Booking\HotelAdapter;
use App\Admin\Support\Facades\Booking\StatusAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;

class BookingController extends AbstractPrototypeController
{
    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $statuses = StatusAdapter::getStatuses();
        $grid = $this->gridFactory($statuses);
//        $query = $this->repository->queryWithCriteria($grid->getSearchCriteria());
//        $this->prepareGridQuery($query);
        $data = AirportAdapter::getBookings();
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

    protected function gridFactory(array $statuses = []): GridContract
    {
        return Grid::enableQuicksearch()
            ->id('id', ['text' => '№', 'route' => $this->prototype->routeName('show'), 'order' => true])
            ->bookingStatus('status', ['text' => 'Статус', 'statuses'=> $statuses])
            ->text('client_name', ['text' => 'Клиент'])
            ->text('city_name', ['text' => 'Город'])
            ->text('service_name', ['text' => 'Название услуги'])
            ->date('date_start', ['text' => 'Дата прилёта/вылета'])
            ->text('flight_number', ['text' => 'Номер рейса'])
            ->number('tourist_count', ['text' => 'Кол-во туристов'])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->paginator(20);
    }

    protected function getPrototypeKey(): string
    {
        return 'airport-booking';
    }
}
