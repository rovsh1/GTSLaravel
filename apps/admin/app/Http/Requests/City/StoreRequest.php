<?php

namespace App\Admin\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Todo доработать правила
        return [
            'data.name' => 'required',
            'data.country_id' => 'required',
            'data.text' => 'string'
        ];
    }
}
