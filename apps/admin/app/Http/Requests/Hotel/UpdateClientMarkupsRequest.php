<?php

namespace App\Admin\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientMarkupsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'individual' => ['required_without_all:OTA,TA,TO', 'numeric'],
            'OTA' => ['required_without_all:individual,TA,TO', 'numeric'],
            'TA' => ['required_without_all:individual,OTA,TO', 'numeric'],
            'TO' => ['required_without_all:individual,OTA,TA', 'numeric'],
        ];
    }

    public function getIndividual(): ?int
    {
        return $this->post('individual');
    }

    public function getOTA(): ?int
    {
        return $this->post('OTA');
    }

    public function getTA(): ?int
    {
        return $this->post('TA');
    }

    public function getTO(): ?int
    {
        return $this->post('TO');
    }
}
