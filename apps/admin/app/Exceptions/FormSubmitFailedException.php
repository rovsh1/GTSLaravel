<?php

namespace App\Admin\Exceptions;

use Illuminate\Contracts\Support\Responsable;

class FormSubmitFailedException extends \RuntimeException implements Responsable
{
    private string $redirectUrl;

    private array $errors;

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function setRedirectUrl(string $url)
    {
        $this->redirectUrl = $url;
    }

    public function toResponse($request)
    {
        return redirect($this->redirectUrl)
            ->withErrors($this->errors)
            ->withInput();
    }
}
