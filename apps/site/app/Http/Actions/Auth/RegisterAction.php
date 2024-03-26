<?php

namespace App\Site\Http\Actions\Auth;

use App\Site\Http\Forms\RegisterForm;
use App\Site\Models\Client;
use App\Site\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Sdk\Shared\Enum\Client\LanguageEnum;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\Client\TypeEnum;
use Sdk\Shared\Enum\Client\User\RoleEnum;

class RegisterAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        $form = new RegisterForm();
        if ($form->submit() && $this->register($form)) {
            $request->session()->regenerate();

            $url = route('home');

            return redirect($url);
        }

        return redirect(route('auth.register'))
            ->withErrors($form->errors());
    }

    private function register(RegisterForm $form): bool
    {
        $data = $form->getData();

        if (User::whereEmail($data['email'])->exists()) {
            $form->error(__('auth.register.form.error.already-registered'));
            $form->throwError();
        }

        if ($data['password'] !== $data['confirm_password']) {
            $form->error(__('auth.register.form.error.different-password'));
            $form->throwError();
        }
        unset($data['confirm_password']);

        DB::beginTransaction();

        $client = Client::create([
            'name' => $data['name'],
            'country_id' => $data['country_id'],
            'is_b2b' => false,
            'type' => TypeEnum::PHYSICAL,
            'status' => 1,
            'residency' => ResidencyEnum::NONRESIDENT,
            'language' => LanguageEnum::RU,//@todo получение языка интерфейса или запрос через поле?
            'markup_group_id' => 1//@todo какая наценка?
        ]);

        $user = User::create([
            'client_id' => $client->id,
            'country_id' => $data['country_id'],
            'presentation' => $data['name'],
            'login' => $data['email'],
            'password' => $data['password'],
            'email' => $data['email'],
            'status' => 1,
            'role' => RoleEnum::CLIENT,
        ]);

        DB::commit();

        //@todo событие зарегистрированного пользователя

        Auth::guard('site')->login($user);

        return $user->isActive();
    }
}
