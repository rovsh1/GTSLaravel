<?php

namespace Pkg\Supplier\Traveline\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractTravelineRequest extends FormRequest
{
    public function rules()
    {
        return [

        ];
    }

    protected function getData(): ?array
    {
        return $this->post('data');
    }
}
