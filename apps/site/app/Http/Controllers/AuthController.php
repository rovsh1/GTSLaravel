<?php

namespace App\Site\Http\Controllers;

use App\Site\Http\Actions\Auth as Actions;
use App\Site\Http\Forms\ForgotPasswordForm;
use App\Site\Http\Forms\LoginForm;
use App\Site\Http\Forms\RegisterForm;
use App\Site\Http\Forms\ResetPasswordForm;
use App\Site\Http\Middleware\TryAuthenticate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(TryAuthenticate::class)->except('logout');
        $this->middleware('guest:site')->except('logout');
    }

    public function index(): View
    {
        return view('auth.login.form', ['form' => new LoginForm('data')]);
    }

    public function login(Request $request): RedirectResponse
    {
        return app(Actions\LoginAction::class)->handle($request);
    }

    public function registerPage(Request $request): View
    {
        return view('auth.register.form', ['form' => new RegisterForm('data')]);
    }

    public function register(Request $request): RedirectResponse
    {
        return app(Actions\RegisterAction::class)->handle($request);
    }

    public function forgotPasswordPage(Request $request): View
    {
        return view('auth.forgot-password.form', ['form' => new ForgotPasswordForm()]);
    }

    public function forgotPasswordSuccessPage(Request $request): View
    {
        return view('auth.forgot-password.success');
    }

    public function forgotPassword(Request $request): RedirectResponse
    {
        return app(Actions\ForgotPasswordAction::class)->handle($request);
    }

    public function resetPasswordPage(Request $request, string $hash): View
    {
        //@todo валидация hash?
        return view('auth.reset-password.form', ['form' => new ResetPasswordForm(['hash' => $hash])]);
    }

    public function resetPassword(Request $request, string $hash): RedirectResponse
    {
        return app(Actions\ResetPasswordAction::class)->handle($request, $hash);
    }

    public function logout(Request $request)
    {
        Auth::guard('site')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('auth.login'));
    }
}
