<?php

namespace App\Admin\View\Menus;

use App\Admin\Support\View\Sidebar\AbstractSubmenu;
use App\Admin\Support\View\Sidebar\Menu\Group;

class ServiceProviderMenu extends AbstractSubmenu
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
            ->addUrl('info', route('service-provider.show', $this->model), 'Описание', ['icon' => 'description'])
            ->addUrl('prices', route('service-provider.service.transfer.prices.index', $this->model), 'Цены', ['icon' => 'currency_ruble']);

        $group = (new Group('services'))
            ->addUrl('services-transfer', route('service-provider.services-transfer.index', $this->model),'Транспорт',['icon' => 'airport_shuttle'])
            ->addUrl('services-airport', route('service-provider.services-airport.index', $this->model),'Аэропорт',['icon' => 'flight_land']);

        $this->addGroup($group);

        $group = (new Group('reference'))
            ->addUrl('seasons', route('service-provider.seasons.index', $this->model), 'Сезоны', ['icon' => 'assignment'])
            ->addUrl('cars', route('service-provider.cars.index', $this->model),'Автомобили', ['icon' => 'airport_shuttle'])
        ;

        $this->addGroup($group);
    }
}
