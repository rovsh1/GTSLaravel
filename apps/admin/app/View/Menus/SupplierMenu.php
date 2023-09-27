<?php

namespace App\Admin\View\Menus;

use App\Admin\Support\View\Sidebar\AbstractSubmenu;
use App\Admin\Support\View\Sidebar\Menu\Group;

class SupplierMenu extends AbstractSubmenu
{
    public function __construct(private $model, string $current = 'info')
    {
        parent::__construct((string)$model, $current);

        $this->build();
    }

    public function title(): string
    {
        return '<div class="name">' . $this->title . '</div>'
            . '<div class="hint">ID: ' . $this->model->id . '</div>';
    }

    private function build()
    {
        $this
            ->addUrl('info', route('supplier.show', $this->model), 'Описание', ['icon' => 'description'])
            ->addUrl('prices', route('supplier.service.transfer.prices.index', $this->model), 'Цены', ['icon' => 'currency_ruble']);

        $group = (new Group('services'))
            ->addUrl('services-transfer', route('supplier.services-transfer.index', $this->model),'Транспорт',['icon' => 'airport_shuttle'])
            ->addUrl('services-airport', route('supplier.services-airport.index', $this->model),'Аэропорт',['icon' => 'flight_land']);

        $this->addGroup($group);

        $group = (new Group('settings'))
            ->addUrl('contracts', route('supplier.contracts.index', $this->model), 'Договора', ['icon' => 'pending_actions'])
            ->addUrl('requisites', route('supplier.requisites.index', $this->model), 'Реквизиты', ['icon' => 'gavel'])
            ->addUrl('seasons', route('supplier.seasons.index', $this->model), 'Сезоны', ['icon' => 'trending_up'])
            ->addUrl('cars', route('supplier.cars.index', $this->model),'Автомобили', ['icon' => 'airport_shuttle'])
            ->addUrl('airports', route('supplier.airports.index', $this->model),'Аэропорты', ['icon' => 'flight_land'])
        ;

        $this->addGroup($group);
    }
}
