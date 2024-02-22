<?php

declare(strict_types=1);

namespace App\Hotel\Http\Requests\PriceRate;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => ['nullable', 'numeric'],
        ];
    }

    public function getRoomId(): ?int
    {
        $roomId = $this->get('room_id');
        if (empty($roomId)) {
            return null;
        }
        return (int)$roomId;
    }
}
