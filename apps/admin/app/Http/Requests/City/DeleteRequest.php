<?php

namespace App\Admin\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Todo доработать правила
        return [
            'id' => 'required|integer'
        ];
    }
}
