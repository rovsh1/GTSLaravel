<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Http\Resources\Manager;
use App\Admin\Models\Administrator\AccessGroup;
use App\Admin\Models\Administrator\Post;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class AdministratorController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'administrator';
    }

    public function get(): JsonResponse
    {
        $managers = $this->repository->query()->get();

        return response()->json(
            Manager::collection($managers)
        );
    }

    protected function formFactory(): FormContract
    {
        $isNew = !isset($this->model);
        $form = Form::name('data')
            ->select('post_id', [
                'label' => 'Должность',
                'emptyItem' => '',
                'items' => Post::get()
            ])
            ->text('presentation', ['label' => 'Имя в системе', 'required' => true])
            ->text('login', ['label' => 'Логин', 'autocomplete' => 'username', 'required' => true])
            ->email('email', ['label' => 'Email', 'autocomplete' => 'email'])
            ->phone('phone', ['label' => 'Телефон'])
            ->select('status', ['label' => 'Статус', 'items' => [0 => 'Заблокирован', 1 => 'Активный']])
            //->addElement('image', 'image', ['label' => 'Аватар'])
            ->password('password', [
                'label' => $isNew ? 'Пароль' : 'Изменить пароль',
                'autocomplete' => 'new-password',
                'required' => $isNew
            ]);

        if (Acl::isUpdateAllowed('access-group')) {
            $form->select('groups', [
                'label' => 'Группы доступа',
                'items' => AccessGroup::get(),
                'multiple' => true
            ]);
        }

        if (Acl::isSuperuser()) {
            $form
//                ->addElement('subscriptions', 'select', [
//                    'label' => 'Уведомления',
//                    'items' => NotificationList::getAdminList(),
//                    'multiple' => true
//                ])
                ->checkbox('superuser', ['label' => 'Суперпользователь']);
        }

        return $form;
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('presentation', ['text' => 'Имя', 'order' => true])
            ->text('login', ['text' => 'Логин', 'order' => true])
            ->text('post_name', ['text' => 'Должность', 'order' => true])
            //->addColumn('role', 'enum', ['text' => 'Роль', 'enum' => AccessRole::class, 'order' => true])
            ->email('email', ['text' => 'Email', 'order' => true])
            ->phone('phone', ['text' => 'Телефон']);
    }

    protected function prepareGridQuery(Builder $query)
    {
        $query
            ->addSelect('administrators.*')
            ->joinPost();
    }
}
