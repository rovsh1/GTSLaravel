<?php

namespace App\Admin\Http\Requests\Hotel;

use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BatchUpdateQuotaRequest extends FormRequest
{
    public const OPEN = 'open';
    public const CLOSE = 'close';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'room_ids' => ['required', 'array'],
            'room_ids.*' => ['required', 'numeric'],
            'action' => ['required', Rule::in([self::OPEN, self::CLOSE])],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'week_days' => ['required', 'array'],
            'week_days.*' => ['required', 'numeric']
        ];
    }

    public function getRoomIds(): array
    {
        return $this->post('room_ids');
    }

    public function getAction(): string
    {
        return $this->post('action');
    }

    public function getPeriod(): CarbonPeriod
    {
        $dateFrom = $this->post('date_from');
        $dateTo = $this->post('date_to');

        return CarbonPeriod::create($dateFrom, $dateTo);
    }

    /**
     * @return int[]
     */
    public function getWeekDays(): array
    {
        return $this->post('week_days');
    }
}
