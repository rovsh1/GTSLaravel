<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;

class AdministratorController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'administrator';
    }

    protected function formFactory()
    {
        $form = (new Form('data'))
            ->text('presentation', ['label' => 'Имя в системе', 'required' => true])
            ->text('login', ['label' => 'Логин', 'autocomplete' => 'username', 'required' => true])
            ->email('email', ['label' => 'Email', 'autocomplete' => 'email'])
            ->phone('phone', ['label' => 'Телефон'])
            //->addElement('status', 'enum', ['label' => 'Статус', 'enum' => UserStatus::class])
            //->addElement('image', 'image', ['label' => 'Аватар'])
            ->password('password', ['label' => 'Пароль', 'autocomplete' => 'new-password', 'required' => false]);


//        if (app('acl')->isAllowed('update access-group')) {
//            $form->select('groups', [
//                'label' => 'Группы доступа',
//                'items' => AccessGroup::get(),
//                'value' => [],
//                'multiple' => true
//            ]);
//        }

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

    protected function gridFactory()
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit(['route' => $this->prototype->routeName('edit')])
            ->text('presentation', ['text' => 'Имя', 'order' => true])
            ->text('login', ['text' => 'Логин', 'order' => true])
            //->addColumn('role', 'enum', ['text' => 'Роль', 'enum' => AccessRole::class, 'order' => true])
            ->email('email', ['text' => 'Email', 'order' => true])
            ->phone('phone', ['text' => 'Телефон']);
    }
}
