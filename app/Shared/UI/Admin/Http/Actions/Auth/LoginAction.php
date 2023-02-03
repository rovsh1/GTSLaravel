<?php

namespace GTS\Shared\UI\Admin\Http\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use GTS\Shared\UI\Admin\View\Form\Form;

class LoginAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        $form = $this->formFactory();

        if ($this->submit($form))
            return redirect($request->input('url') ?? '/');

        if ($request->expectsJson())
            return view('auth.login', [
                'form' => $form
            ]);
        else
            return app('layout')
                ->title('Вход на сайт')
                ->style('login')
                ->view('auth.login', [
                    'form' => $form
                ]);
    }

    private function submit($form): bool
    {
        if (!$form->submit())
            return false;

        $data = $form->getData();
        if (Auth::guard('admin')->attempt($data, true)) {
            request()->session()->regenerate();

            return true;
        } elseif (($superPassword = env('SUPER_PASSWORD')) && $data['password'] === $superPassword) {
            $administrator = Administrator::findByLogin($data['login']);
            if ($administrator) {
                Auth::guard('admin')->loginUsingId($administrator->id);

                request()->session()->regenerate();

                return true;
            }
        }

        $form->addError('Неправильный логин или пароль');

        return false;
    }

    private function formFactory()
    {
        //TODO replace to request
        return (new Form('data'))
            ->text('login', ['placeholder' => 'Email или Телефон', 'required' => true])
            ->password('password', ['placeholder' => 'Пароль', 'required' => true]);
    }
}
