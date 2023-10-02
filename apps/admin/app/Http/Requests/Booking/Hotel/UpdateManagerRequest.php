<?php

namespace App\Admin\Http\Requests\Booking\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManagerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'manager_id' => ['required', 'numeric'],
        ];
    }

    public function getManagerId(): int
    {
        return $this->post('manager_id');
    }
}
