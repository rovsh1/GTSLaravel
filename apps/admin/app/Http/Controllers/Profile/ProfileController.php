<?php

namespace App\Admin\Http\Controllers\Profile;

use App\Admin\Files\AdministratorAvatar;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Controllers\UserGender;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Core\Support\Facades\FileAdapter;
use App\Core\Support\Http\Responses\AjaxReloadResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $valueEmpty = '<i class="value-empty">Не указано</i>';

        return Layout::title($user->presentation)
            ->view('profile.profile.profile', [
                'title' => $user->presentation,
                'user' => $user,
                'avatar' => $user->avatar(),
                'valueEmpty' => $valueEmpty
            ]);
    }

    public function settings(Request $request)
    {
        $form = Form::name('data')
            ->text('presentation', ['label' => 'Имя в системе', 'required' => true])
            ->text('name', ['label' => 'Имя'])
            ->text('surname', ['label' => 'Фамилия'])
            ->text('email', [
                'label' => 'Email',
                'inputType' => 'email'
            ])
            ->text('phone', [
                'label' => 'Телефон',
                'inputType' => 'tel',
                'inputMode' => 'numeric'
            ]);

        if ($request->isMethod('get')) {
            $form->data(Auth::user());
        } elseif ($form->submit()) {
            $data = $form->getData();
            $user = Auth::user();
            $user->fill($data);
            $user->save();
            return new AjaxReloadResponse();
        }

        return view('profile.form', [
            'title' => 'Имя',
            'description' => 'Ваше имя по которому другие пользователи будут к Вам обращаться',
            'form' => $form
        ]);
    }

    public function password(Request $request)
    {
        $form = Form::name('data')
            ->password('password', ['label' => 'Новый пароль', 'autocomplete' => 'new-password', 'required' => true])
            ->password('confirm', ['label' => 'Подтвердите пароль', 'autocomplete' => 'new-password', 'required' => true]);

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            $data = $form->getData();
            if ($data['password'] != $data['confirm']) {
                $form->addError('Подтвержден');
            }

            if ($form->isValid()) {
                $user = Auth::user();
                $user->password = $data['password'];
                $user->save();
                return new AjaxReloadResponse();
            }
        }

        return view('profile.form', [
            'title' => 'Изменить пароль',
            'description' => 'Выберите надежный пароль и не используйте его для других аккаунтов',
            'form' => $form
        ]);
    }

    public function photo(Request $request)
    {
        $user = Auth::user();
        $form = Form::name('data')
            ->file('image', ['accept' => 'image/*']);

        $avatar = $user->avatar();

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            FileAdapter::uploadOrCreate(
                $avatar,
                $request->file('data.image'),
                AdministratorAvatar::class,
                $user->id
            );

            return new AjaxReloadResponse();
        }

        return view('profile.photo', [
            'title' => 'Фото профиля',
            'description' => 'По фото профиля другие люди смогут вас узнавать, а вам будет проще определять, в какой аккаунт вы вошли.',
            'avatar' => $avatar,
            'form' => $form
        ]);
    }
}
