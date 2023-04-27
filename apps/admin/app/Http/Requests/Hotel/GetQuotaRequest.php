<?php

namespace App\Admin\Http\Requests\Hotel;

use App\Core\Validation\Rules\DateIntervalRule;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class GetQuotaRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'month' => ['required', 'numeric', 'between:1,12'],
            'year' => ['required', 'date_format:Y'],
            'interval' => ['required', new DateIntervalRule],
            'availability' => ['nullable', 'numeric']
        ];
    }

    public function getAvailability(): int
    {
        return $this->get('availability');
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
