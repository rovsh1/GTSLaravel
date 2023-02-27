<?php

namespace App\Admin\Http\Requests\Country;

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
            'data.language' => 'string',
            'data.flag' => 'required',
            'data.phone_code' => 'required',
            'data.currency_id' => 'integer',
            'data.default' => 'integer'
        ];
    }
}
