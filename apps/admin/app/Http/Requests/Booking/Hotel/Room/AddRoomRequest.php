<?php

namespace App\Admin\Http\Requests\Booking\Hotel\Room;

use Illuminate\Foundation\Http\FormRequest;

class AddRoomRequest extends FormRequest
{
    public function rules()
    {
        return [
            'room_id' => ['required', 'numeric'],
            'status' => ['required', 'numeric'],
            'is_resident' => ['required', 'boolean'],
            'rate_id' => ['required', 'numeric'],
            'note' => ['nullable', 'string'],
            'discount' => ['nullable', 'numeric'],
            'late_check_out' => ['nullable', 'array'],
            'early_check_in' => ['nullable', 'array'],
        ];
    }

    public function getRoomId(): int
    {
        return $this->post('room_id');
    }

    public function getStatus(): int
    {
        return $this->post('status');
    }

    public function getIsResident(): bool
    {
        return $this->post('is_resident');
    }

    public function getRateId(): int
    {
        return $this->post('rate_id');
    }

    public function getNote(): ?string
    {
        return $this->post('note');
    }

    public function getDiscount(): ?int
    {
        return $this->post('discount');
    }

    public function getLateCheckOut(): ?array
    {
        return $this->post('late_check_out');
    }

    public function getEarlyCheckIn(): ?array
    {
        return $this->post('early_check_in');
    }
}
