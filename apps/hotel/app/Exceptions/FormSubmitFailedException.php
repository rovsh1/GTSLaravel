<?php

namespace App\Hotel\Exceptions;

use Illuminate\Contracts\Support\Responsable;

class FormSubmitFailedException extends \Exception implements Responsable
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
