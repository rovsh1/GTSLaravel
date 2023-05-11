<?php

namespace App\Admin\Http\Requests\Hotel;

use App\Admin\Support\Adapters\Hotel\QuotaAvailabilityEnum;
use App\Core\Validation\Rules\DateIntervalRule;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class GetQuotaRequest extends FormRequest
{

    /** @var array<string, QuotaAvailabilityEnum> $availabilityMap */
    private array $availabilityMap = [
        'sold' => QuotaAvailabilityEnum::SOLD,
        'stopped' => QuotaAvailabilityEnum::STOPPED,
        'available' => QuotaAvailabilityEnum::AVAILABLE,
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => ['nullable', 'numeric'],
            'month' => ['required', 'numeric', 'between:1,12'],
            'year' => ['required', 'date_format:Y'],
            'interval' => ['required', new DateIntervalRule],
            'availability' => ['nullable', 'in:sold,stopped,available']
        ];
    }

    public function getAvailability(): ?QuotaAvailabilityEnum
    {
        $availability = $this->get('availability');
        if ($availability === null) {
            return null;
        }
        return $this->availabilityMap[$availability];
    }

    public function getRoomId(): ?int
    {
        return $this->get('room_id');
    }

    public function getPeriod(): CarbonPeriod
    {
        $dateFrom = Carbon::create($this->getYear(), $this->getMonth());
        $dateTo = $dateFrom->clone()->add($this->getInterval());
        return new CarbonPeriod($dateFrom, $dateTo);
    }

    private function getMonth(): int
    {
        return $this->get('month');
    }

    private function getYear(): int
    {
        return $this->get('year');
    }

    private function getInterval(): CarbonInterval
    {
        return new CarbonInterval($this->get('interval'));
    }
}
