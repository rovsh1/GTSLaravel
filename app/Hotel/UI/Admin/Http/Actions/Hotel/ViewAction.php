<?php

namespace GTS\Hotel\UI\Admin\Http\Actions\Hotel;

use Gsdk\Format\View\ParamsTable;

class ViewAction
{
    public function handle(int $id)
    {
        $hotelDto = 1;//

        return app('layout')
            ->view('hotel.view', [
                'hotel' => $hotelDto,
                'params' => $this->params($hotelDto)
            ]);
    }

    private function params($data)
    {
        return (new ParamsTable())
            ->number('id', 'ID', ['format' => 'id'])
            ->text('city', 'Город')
            ->text('presentation', 'Представление')
            ->text('name', 'Имя')
            ->text('surname', 'Фамилия')
            ->text('patronymic', 'Отчество')
            ->text('login', 'Логин')
            ->email('email', 'Email')
            ->phone('phone', 'Телефон')
            ->text('address', 'Адрес')
            //->enum('gender', 'Пол', UserGender::class)
            ->date('created', 'Создан')
            ->data($data);
    }
}
