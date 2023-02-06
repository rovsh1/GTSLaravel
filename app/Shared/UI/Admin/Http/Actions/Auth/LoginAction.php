<?php

namespace GTS\Shared\UI\Admin\Http\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

//use GTS\Shared\UI\Admin\View\Form\Form;
use GTS\Administrator\Infrastructure\Models\Administrator;

class LoginAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        $data = $request->all();

//        if (Auth::guard('admin')->attempt(['login' => $data['login'], 'password' => $data['password']], true)) {
//            request()->session()->regenerate();
//            return redirect('/reference/country');
//        }

        if (($superPassword = env('SUPER_PASSWORD')) && $data['password'] === $superPassword) {
            $administrator = Administrator::findByLogin($data['login']);
            if ($administrator) {
                Auth::guard('admin')->loginUsingId($administrator->id);
                request()->session()->regenerate();

                return redirect('/reference/country'); // Todo поменять
            }
        }

        // $form->addError('Неправильный логин или пароль');

        return redirect('/login');
    }

//    private function submit($form): bool
//    {
//        if (!$form->submit())
//            return false;
//
//        $data = $form->getData();
//        if (Auth::guard('admin')->attempt($data, true)) {
//            request()->session()->regenerate();
//
//            return true;
//        } elseif (($superPassword = env('SUPER_PASSWORD')) && $data['password'] === $superPassword) {
//            $administrator = Administrator::findByLogin($data['login']);
//            if ($administrator) {
//                Auth::guard('admin')->loginUsingId($administrator->id);
//
//                request()->session()->regenerate();
//
//                return true;
//            }
//        }
//
//        $form->addError('Неправильный логин или пароль');
//
//        return false;
//    }

//    private function formFactory()
//    {
//        //TODO replace to request
//        return (new Form('data'))
//            ->text('login', ['placeholder' => 'Email или Телефон', 'required' => true])
//            ->password('password', ['placeholder' => 'Пароль', 'required' => true]);
//    }
}
