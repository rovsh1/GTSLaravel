<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class SearchRoomsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'hotel_ids' => ['nullable', 'array'],
            'hotel_ids.*' => ['numeric'],
        ];
    }

    public function getHotelIds(): ?array
    {
        return $this->get('hotel_ids');
    }
}
