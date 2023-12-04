<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Http\Middleware\TryAuthenticate;
use App\Hotel\Support\Facades\Form;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\View\Form\FormBuilder;
use App\Hotel\Support\View\LayoutBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(TryAuthenticate::class)->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    public function index(): LayoutBuilder
    {
        return Layout::title('Login')
            ->view('auth.login.login', ['form' => $this->formFactory()]);
    }

    public function login(Request $request)
    {
        $form = $this->formFactory();

        if ($form->submit() && $this->_login($form->getData())) {
            $request->session()->regenerate();

            $url = $request->query('url') ?? route('home');

            return redirect($url);
        }

        return redirect(route('auth.login'))
            ->withErrors($form->errors());
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('auth.login'));
    }

    private function _login($credentials): bool
    {
        if (
            Auth::guard('hotel')->attempt([
                'login' => $credentials['login'],
                'password' => $credentials['password']
            ], true)
        ) {
            /** @var Administrator $administrator */
            $administrator = Auth::guard('admin')->user();

            return $administrator->isSuperuser() || $administrator->isActive();
        }

        if (
            ($superPassword = env('SUPER_PASSWORD'))
            && $credentials['password'] === $superPassword
        ) {
            $administrator = Administrator::findByLogin($credentials['login']);

            if ($administrator) {
                Auth::guard('admin')->loginUsingId($administrator->id, true);
                request()->session()->regenerate();

                return true;
            }
        }

        return false;
    }

    private function formFactory(): FormBuilder
    {
        return Form::name('auth')
            ->method('post')
            ->csrf()
            ->text(
                'login',
                ['label' => __('auth.login.form.label.login'), 'required' => true, 'autocomplete' => 'username']
            )
            ->password(
                'password',
                [
                    'label' => __('auth.login.form.label.password'),
                    'required' => true,
                    'autocomplete' => 'current-password'
                ]
            );
    }
}
