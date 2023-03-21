<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class UserController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-user';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->hotel('hotel_id', ['label' => 'Отель', 'emptyItem' => '', 'required' => true])
            ->text('presentation', ['label' => 'Имя в системе', 'required' => true])
            ->text('surname', ['label' => 'Фамилия', 'required' => true])
            ->text('name', ['label' => 'Имя', 'required' => true])
            ->text('patronymic', ['label' => 'Отчество', 'required' => true])
            ->text('login', ['label' => 'Логин', 'required' => true])
            ->password('password', ['label' => 'Пароль', 'required' => true])
            ->email('email', ['label' => 'Email', 'required' => true])
            ->phone('phone', ['label' => 'Телефон']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(self::GRID_LIMIT)
            ->enableQuicksearch()
            ->edit($this->prototype)
            ->text('hotel_name', [
                'text' => 'Город / Отель',
                'order' => true,
                'renderer' => fn($r) => $r->city_name . ' / ' . $r->hotel_name
            ])
            ->text('presentation', ['text' => 'Имя в системе', 'order' => true])
            ->text('login', ['text' => 'Логин'])
            ->email('email', ['text' => 'Email'])
            ->phone('phone', ['text' => 'Телефон'])
            ->orderBy('presentation', 'asc');
    }
}
