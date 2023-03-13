<?php

namespace App\Admin\View\Menus;

use App\Admin\Support\View\Sidebar\AbstractSubmenu;
use App\Admin\Support\View\Sidebar\Menu\Group;

class HotelMenu extends AbstractSubmenu
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
            ->addUrl('prices', route('hotels.rooms.index', $this->model->id), 'Цены')
            ->addUrl('reservations', route('hotels.rooms.index', $this->model->id), 'Брони')
            ->addUrl('info', route('hotels.show', $this->model->id), 'Описание');

        $group = (new Group('settings'))
            ->addUrl('quota', route('hotels.rooms.index', $this->model->id), 'Квоты')
            ->addUrl('images', route('hotels.rooms.index', $this->model->id), 'Фотографии')
            ->addUrl('rooms', route('hotels.rooms.index', $this->model->id), 'Номера')
            ->addUrl('settings', route('hotels.rooms.index', $this->model->id), 'Условия размещения');
        $this->addGroup($group);

        $group = (new Group('additional'))
            ->addUrl('reviews', route('hotels.rooms.index', $this->model->id), 'Отзывы')
            ->addUrl('journal', route('hotels.rooms.index', $this->model->id), 'Журнал');
        $this->addGroup($group);
    }
}
