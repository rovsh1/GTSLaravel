<?php

namespace App\Admin\Http\Requests\Hotel;

use App\Core\Validation\Rules\DateIntervalRule;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class GetQuotaRequest extends FormRequest
{
    public const AVAILABILITY_SOLD = 'sold';
    public const AVAILABILITY_STOPPED = 'stopped';
    public const AVAILABILITY_AVAILABLE = 'available';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => ['nullable', 'numeric'],
            'dateFrom' => ['required', 'date'],
            'dateTo' => ['required', 'date'],
            'availability' => ['nullable', 'in:sold,stopped,available']
        ];
    }

    public function getAvailability(): ?string
    {
        return $this->get('availability');
    }

    public function getRoomId(): ?int
    {
        return $this->get('room_id');
    }

    public function getPeriod(): CarbonPeriod
    {
        $dateFrom = $this->date('dateFrom');
        $dateTo = $this->date('dateTo');
        return new CarbonPeriod($dateFrom, $dateTo);
    }
}
