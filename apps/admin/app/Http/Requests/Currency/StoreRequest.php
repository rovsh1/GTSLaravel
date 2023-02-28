<?php

namespace App\Admin\Http\Requests\Currency;

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
            'data.code_num' => 'required',
            'data.code_char' => 'required',
            'data.sign' => 'required'
        ];
    }
}
