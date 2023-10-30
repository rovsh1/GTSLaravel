<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Shared\Http\Responses\AjaxReloadResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Module\Administrator\Application\UseCase\UpdateAvatar;
use Module\Booking\Application\Admin\Booking\UseCase\TestUseCase;
use Module\Shared\Dto\UploadedFileDto;

class ProfileController extends Controller
{
    public function index()
    {
        app(TestUseCase::class)->execute();
        $user = Auth::user();
        $valueEmpty = '<i class="value-empty">Не указано</i>';

        return Layout::title($user->presentation)
            ->view('profile.profile.profile', [
                'title' => $user->presentation,
                'user' => $user,
                'avatar' => $user->avatar,
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
            ->password(
                'confirm',
                ['label' => 'Подтвердите пароль', 'autocomplete' => 'new-password', 'required' => true]
            );

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

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            $uploadedFile = $request->file('data.image');
            app(UpdateAvatar::class)->execute(
                $user->id,
                UploadedFileDto::fromUploadedFile($uploadedFile)
            );

            return new AjaxReloadResponse();
        }

        return view('profile.photo', [
            'title' => 'Фото профиля',
            'description' => 'По фото профиля другие люди смогут вас узнавать, а вам будет проще определять, в какой аккаунт вы вошли.',
            'avatar' => $user->avatar,
            'form' => $form
        ]);
    }
}
