<?php

namespace App\Admin\Http\Requests\Order\Tourist;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tourist_id' => ['required', 'numeric'],
        ];
    }

    public function getTouristId(): int
    {
        return $this->post('tourist_id');
    }
}
