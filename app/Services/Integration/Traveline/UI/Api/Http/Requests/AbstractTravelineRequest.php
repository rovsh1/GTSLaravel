<?php

namespace GTS\Services\Integration\Traveline\UI\Api\Http\Requests;

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
