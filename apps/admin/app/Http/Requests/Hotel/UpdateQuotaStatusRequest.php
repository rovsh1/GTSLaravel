<?php

namespace App\Admin\Http\Requests\Hotel;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuotaStatusRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'dates' => ['required', 'array'],
            'dates.*' => ['required', 'date'],
            'open' => ['required_without:close', 'missing_with:close', 'boolean'],
            'close' => ['required_without:open', 'missing_with:open', 'boolean'],
        ];
    }

    /**
     * @return CarbonInterface[]
     */
    public function getDates(): array
    {
        return array_map(fn(string $date) => new Carbon($date), $this->post('dates'));
    }

    public function isClose(): ?bool
    {
        return $this->post('close');
    }

    public function isOpen(): ?bool
    {
        return $this->post('open');
    }
}
