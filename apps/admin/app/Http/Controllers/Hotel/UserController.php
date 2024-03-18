<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\User;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Contracts\View\View;

class UserController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-user';
    }

    public function index(): LayoutContract
    {
        return parent::index()->view('hotel-user.main.main');
    }

    public function createDialog(Hotel $hotel): View
    {
        $form = $this->dialogForm($hotel->id)
            ->method('post')
            ->action(route('hotels.users.store.dialog', $hotel));

        return view('default.dialog-form', [
            'form' => $form
        ]);
    }

    public function storeDialog(Hotel $hotel): AjaxResponseInterface
    {
        $form = $this->dialogForm($hotel->id)
            ->method('post');

        $form->submitOrFail(route('hotels.users.create.dialog', $hotel));
        $preparedData = $this->saving($form->getData());
        $this->model = $this->repository->create($preparedData);

        return new AjaxReloadResponse();
    }

    public function editDialog(Hotel $hotel, User $user): View
    {
        $this->model = $user;

        $form = $this->dialogForm($hotel->id)
            ->method('put')
            ->action(route('hotels.users.update.dialog', [$hotel, $user]))
            ->data($user);

        return view('default.dialog-form', [
            'form' => $form
        ]);
    }

    public function updateDialog(Hotel $hotel, User $user): AjaxResponseInterface
    {
        $this->model = $user;

        $form = $this->dialogForm($hotel->id)
            ->method('put')
            ->failUrl(route('hotels.users.edit.dialog', [$hotel, $user]));

        $form->submitOrFail();
        $preparedData = $this->saving($form->getData());
        $this->repository->update($user->id, $preparedData);

        return new AjaxReloadResponse();
    }

    public function destroyDialog(Hotel $hotel, User $user): AjaxResponseInterface
    {
        $this->repository->delete($user->id);

        return new AjaxReloadResponse();
    }

    private function dialogForm(int $hotelId): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->text('presentation', ['label' => 'Имя в системе (ФИО)', 'required' => true])
            ->text('login', ['label' => 'Логин', 'required' => true])
            ->password('password', ['label' => 'Пароль', 'required' => empty($this->model)])
            ->email('email', ['label' => 'Email', 'required' => true])
            ->phone('phone', ['label' => 'Телефон']);
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->hotel('hotel_id', ['label' => 'Отель', 'emptyItem' => '', 'required' => true])
            ->text('presentation', ['label' => 'Имя в системе (ФИО)', 'required' => true])
            ->text('login', ['label' => 'Логин', 'required' => true])
            ->password('password', ['label' => 'Пароль', 'required' => empty($this->model)])
            ->email('email', ['label' => 'Email', 'required' => true])
            ->phone('phone', ['label' => 'Телефон']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(self::GRID_LIMIT)
            ->enableQuicksearch()
            ->setSearchForm($this->buildSearchForm())
            ->edit($this->prototype)
            ->text('hotel_name', [
                'text' => 'Город / Отель',
                'order' => true,
                'renderer' => fn($r) => $r->city_name . ' / ' . $r->hotel_name
            ])
            ->text('presentation', ['text' => 'Имя в системе (ФИО)', 'order' => true])
            ->text('login', ['text' => 'Логин'])
            ->email('email', ['text' => 'Email'])
            ->phone('phone', ['text' => 'Телефон'])
            ->orderBy('presentation', 'asc');
    }

    private function buildSearchForm()
    {
        return (new SearchForm())
            ->country('country_id', ['label' => 'Страна', 'emptyItem' => ''])
            ->hidden('city_id', ['label' => 'Город'])
            ->hidden('hotel_id', ['label' => 'Отель']);
    }
}
