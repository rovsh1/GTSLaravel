<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Http\Forms\Auth\LoginForm;
use App\Hotel\Http\Forms\Auth\PartnerForm;
use App\Hotel\Http\Forms\Auth\RecoveryForm;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\Http\AbstractController;
use App\Hotel\Support\View\LayoutBuilder;
use Illuminate\Http\Request;

class AuthController extends AbstractController
{
    public function login(): LayoutBuilder
    {
        return Layout::title('Login')
            ->view('auth.login.login', ['form' => new LoginForm()]);
    }

    public function processLogin(Request $request)
    {
        $form = new LoginForm();
        if ($form->submit()) {
            return redirect($request->query('url') ?? route('home'));
        }

        return redirect(route('auth.login'))
            ->withErrors($form->errors());
    }

    public function recovery(): LayoutBuilder
    {
        return Layout::title('Recovery')
            ->view('auth.recovery.recovery', ['form' => new RecoveryForm()]);
    }

    public function processRecovery(Request $request)
    {
        $form = new RecoveryForm();
        if ($form->submit()) {
            return redirect($request->query('url') ?? route('home'));
        }

        return redirect(route('recovery'))
            ->withErrors($form->errors());
    }

    public function partner(): LayoutBuilder
    {
        return Layout::title('Partner')
            ->view('auth.partner.partner', ['form' => new PartnerForm()]);
    }

    public function processPartner(Request $request)
    {
        $form = new PartnerForm();
        if ($form->submit()) {
            return redirect($request->query('url') ?? route('home'));
        }

        return redirect(route('partner'))
            ->withErrors($form->errors());
    }
}
