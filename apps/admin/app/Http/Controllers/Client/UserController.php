<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Models\Reference\Country;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use Module\Shared\Enum\Client\User\RoleEnum;
use Module\Shared\Enum\Client\User\StatusEnum;

class UserController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'client-user';
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->text(
                'name',
                [
                    'text' => 'ФИО',
                    'route' => $this->prototype->routeName('edit'),
                    'renderer' => fn($user) => (string)$user
                ]
            )
            ->text('city_name', ['text' => 'Город / Клиент'])
            ->text('email', ['text' => 'Email'])
            ->text('phone', ['text' => 'Телефон']);
    }

    protected function formFactory(): FormContract
    {
        return Form::client('client_id', ['label' => 'Клиент', 'required' => true, 'emptyItem' => ''])
            ->text('presentation', ['label' => 'Имя в системе', 'required' => true])
            ->text('surname', ['label' => 'Фамилия', 'required' => true])
            ->text('name', ['label' => 'Имя', 'required' => true])
            ->text('patronymic', ['label' => 'Отчество', 'required' => true])
            ->text('email', ['label' => 'Email', 'required' => true])
            ->text('phone', ['label' => 'Телефон'])
            ->text('password', ['label' => 'Пароль', 'required' => true])
            ->hidden('status', ['value' => StatusEnum::ACTIVE->value])
            ->hidden('role', ['value' => RoleEnum::CUSTOMER->value]);
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->select('country_id', ['label' => 'Страна', 'emptyItem' => '', 'items' => Country::get()])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => '']);
    }
}
