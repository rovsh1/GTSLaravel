<?php

namespace App\Admin\View\Menus;

use App\Admin\Support\View\Sidebar\AbstractSubmenu;

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
//            ->add('Цены')
//            ->add('Брони')
//            ->add('Квоты')
//            ->add('Журнал')
            ->addUrl('info', route('hotels.show', $this->model->id), 'Описание')
            ->addUrl('rooms', route('hotels.rooms.index', $this->model->id), 'Номера')
//            ->addRoute('hotel.images', 'Фотографии')
//            ->add('Условия размещения')
//            ->add('Отзывы')
        ;
    }
}
