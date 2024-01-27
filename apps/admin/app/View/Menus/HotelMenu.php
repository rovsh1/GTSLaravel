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
            ->addUrl('prices', route('hotels.prices.index', $this->model->id), 'Цены', ['icon' => 'currency_ruble'])
            ->addUrl('bookings', route('hotels.bookings.index', $this->model->id), 'Брони', ['icon' => 'sell'])
            ->addUrl('info', route('hotels.show', $this->model->id), 'Описание', ['icon' => 'description'])
            ->addUrl('employees', route('hotels.employee.index', $this->model->id), 'Сотрудники', ['icon' => 'person']);

        $group = (new Group('settings'))
            ->addUrl('quotas', route('hotels.quotas.index', $this->model->id), 'Квоты', ['icon' => 'edit_calendar'])
            ->addUrl('images', route('hotels.images.index', $this->model->id), 'Фотографии', ['icon' => 'image'])
            ->addUrl('rooms', route('hotels.rooms.index', $this->model->id), 'Номера', ['icon' => 'single_bed'])
            ->addUrl('settings', route('hotels.settings.index', $this->model->id), 'Условия размещения', ['icon' => 'tune'])
            ->addUrl('contracts', route('hotels.contracts.index', $this->model->id), 'Договора', ['icon' => 'pending_actions'])
            ->addUrl('seasons', route('hotels.seasons.index', $this->model->id), 'Сезоны', ['icon' => 'trending_up'])
            ->addUrl('rates', route('hotels.rates.index', $this->model->id), 'Тарифы', ['icon' => 'corporate_fare']);
        $this->addGroup($group);

        $group = (new Group('additional'))
            ->addUrl('reviews', route('hotels.reviews.index', $this->model->id), 'Отзывы', ['icon' => 'comment'])
            ->addUrl('journal', route('hotels.rooms.index', $this->model->id), 'Журнал', ['icon' => 'history']);
        $this->addGroup($group);
    }
}
