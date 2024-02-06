<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Shared\Http\Responses\AjaxRedirectResponse;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

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
            ->failUrl(route('profile.password'))
            ->password(
                'password',
                ['label' => 'Новый пароль', 'autocomplete' => 'new-password', 'required' => true, 'minlength' => 6]
            )
            ->password(
                'confirm',
                [
                    'label' => 'Подтвердите пароль',
                    'autocomplete' => 'new-password',
                    'required' => true,
                    'minlength' => 6
                ]
            );

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            $data = $form->getData();
            if ($data['password'] != $data['confirm']) {
                $form->error('Пароли не совпадают');
                $form->throwError();
            }

            if ($form->isValid()) {
                $user = Auth::user();
                $user->password = $data['password'];
                $user->save();

                return redirect(route('profile'));
            }
        }

        return Layout::title('Изменить пароль')
            ->view('profile.password.form', [
                'description' => 'Выберите надежный пароль и не используйте его для других аккаунтов',
                'form' => $form,
                'cancelUrl' => route('profile')
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
            $this->updateAvatar($user, $uploadedFile);

            return new AjaxReloadResponse();
        }

        return view('profile.photo', [
            'title' => 'Фото профиля',
            'description' => 'По фото профиля другие люди смогут вас узнавать, а вам будет проще определять, в какой аккаунт вы вошли.',
            'avatar' => $user->avatar,
            'form' => $form
        ]);
    }

    public function delete(Request $request): AjaxResponseInterface
    {
        $user = Auth::user();
        $this->logout($request);
        $user->delete();

        return new AjaxRedirectResponse(
            route('auth.login')
        );
    }

    private function updateAvatar(Administrator $model, UploadedFile $uploadedFile): void
    {
        $fileStorageAdapter = app(FileStorageAdapterInterface::class);
        $fileDto = $fileStorageAdapter->updateOrCreate(
            $model->avatar?->guid,
            $uploadedFile->getClientOriginalName(),
            $uploadedFile->get()
        );
        if ($fileDto) {
            $model->update(['avatar' => $fileDto->guid]);
        }
    }

    private function logout(Request $request): void
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
