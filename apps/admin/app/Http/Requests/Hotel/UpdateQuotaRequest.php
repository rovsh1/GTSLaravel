<?php

namespace App\Admin\Http\Requests\Hotel;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuotaRequest extends FormRequest
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
            'count' => ['required', 'numeric'],
        ];
    }

    /**
     * @return CarbonInterface[]
     */
    public function getDates(): array
    {
        return array_map(fn(string $date) => new Carbon($date), $this->post('dates'));
    }

    public function getCount(): int
    {
        return $this->post('count');
    }
}
