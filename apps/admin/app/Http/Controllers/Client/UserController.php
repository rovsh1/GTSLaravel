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
use App\Admin\Support\View\Layout as LayoutContract;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Http\RedirectResponse;
use Module\Shared\Enum\Client\User\RoleEnum;
use Module\Shared\Enum\Client\User\StatusEnum;
use Module\Shared\Enum\GenderEnum;

class UserController extends AbstractPrototypeController
{
    private bool $isEdit = false;

    protected function getPrototypeKey(): string
    {
        return 'client-user';
    }

    protected function getShowViewData(): array
    {
        return [
            'params' => $this->showParams(),
        ];
    }

    public function edit(int $id): LayoutContract
    {
        $this->isEdit = true;

        return parent::edit($id);
    }

    public function update(int $id): RedirectResponse
    {
        $this->isEdit = true;

        return parent::update($id);
    }

    private function showParams(): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('presentation', 'Имя в системе')
            ->text('client_name', 'Клиент')
            ->text('surname', 'Фамилия')
            ->text('name', 'Имя')
            ->text('patronymic', 'Отчество')
            ->enum('gender', 'Пол', GenderEnum::class)
            ->text('email', 'Email')
            ->text('phone', 'Телефон')
            ->date('created_at', 'Создан')
            ->data($this->model);
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
                    'route' => $this->prototype->routeName('show'),
                    'renderer' => fn($user) => (string)$user,
                    'order' => true
                ]
            )
            ->text(
                'city_name',
                [
                    'text' => 'Город / Клиент',
                    'renderer' => function ($row, $city) {
                        $clientName = $row['client_name'] ?? null;
                        if ($city && $clientName) {
                            return $city . ' / ' . $clientName;
                        } elseif ($city && !$clientName) {
                            return $city;
                        } elseif (!$city && $clientName) {
                            return $clientName;
                        }

                        return '';
                    }
                ]
            )
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
            ->password('password', ['label' => 'Пароль', 'required' => !$this->isEdit])
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
