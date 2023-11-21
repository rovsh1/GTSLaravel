<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'numeric'],
        ];
    }

    /**
     * @return int[]
     */
    public function getIds(): array
    {
        return $this->post('ids');
    }
}
