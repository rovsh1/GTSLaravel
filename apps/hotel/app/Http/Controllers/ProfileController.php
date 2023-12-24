<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Models\User;
use App\Hotel\Support\Facades\Form;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\Http\AbstractController;
use App\Shared\Http\Responses\AjaxReloadResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

class ProfileController extends AbstractController
{
    public function index()
    {
        /** @var User $user */
        $user = $this->getUser();
        $valueEmpty = '<i class="value-empty">Не указано</i>';

        return Layout::title($user->presentation)
            ->view('profile.profile.profile', [
                'title' => $user->presentation,
                'user' => $user,
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
            $form->data($this->getUser());
        } elseif ($form->submit()) {
            $data = $form->getData();
            $user = $this->getUser();
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
                $user = $this->getUser();
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
        $form = Form::name('data')
            ->file('image', ['accept' => 'image/*']);

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            $uploadedFile = $request->file('data.image');

//            $this->updateAvatar($user, $uploadedFile);

            return new AjaxReloadResponse();
        }

        return view('profile.photo', [
            'title' => 'Фото профиля',
            'description' => 'По фото профиля другие люди смогут вас узнавать, а вам будет проще определять, в какой аккаунт вы вошли.',
//            'avatar' => $user->avatar,
            'form' => $form
        ]);
    }

    private function updateAvatar(User $model, UploadedFile $uploadedFile): void
    {
        $fileStorageAdapter = app(FileStorageAdapterInterface::class);
        $fileDto = $fileStorageAdapter->updateOrCreate(
            $model->avatar,
            $uploadedFile->getClientOriginalName(),
            $uploadedFile->get()
        );
        if ($fileDto) {
            $model->update(['avatar' => $fileDto->guid]);
        }
    }

    private function getUser(): User
    {
        return Auth::guard('hotel')->user();
    }
}
