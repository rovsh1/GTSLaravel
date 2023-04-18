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
            ->addUrl('seasons', route('service-provider.show', $this->model), 'Сезоны', ['icon' => 'assignment'])
            ->addUrl('services', route('service-provider.services.index', $this->model), 'Услуги', ['icon' => 'lan'])
            ->addUrl('prices', route('service-provider.show', $this->model), 'Цены', ['icon' => 'currency_ruble']);
    }
}
