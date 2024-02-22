<?php

namespace App\Hotel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'time_settings' => ['required', 'array'],
            'time_settings.checkInAfter' => ['required', 'string'],
            'time_settings.checkOutBefore' => ['required', 'string'],
            'time_settings.breakfastFrom' => ['required_with:time_settings.breakfastTo', 'nullable', 'string'],
            'time_settings.breakfastTo' => ['required_with:time_settings.breakfastFrom', 'nullable', 'string'],
        ];
    }

    public function getCheckInAfter(): string
    {
        $timeSettings = $this->getTimeSettings();

        return \Arr::get($timeSettings, 'checkInAfter');
    }

    public function getCheckOutBefore(): string
    {
        $timeSettings = $this->getTimeSettings();

        return \Arr::get($timeSettings, 'checkOutBefore');
    }

    public function getBreakfastPeriodFrom(): ?string
    {
        $timeSettings = $this->getTimeSettings();

        return \Arr::get($timeSettings, 'breakfastFrom');
    }

    public function getBreakfastPeriodTo(): ?string
    {
        $timeSettings = $this->getTimeSettings();

        return \Arr::get($timeSettings, 'breakfastTo');
    }

    private function getTimeSettings(): array
    {
        return $this->post('time_settings');
    }
}
