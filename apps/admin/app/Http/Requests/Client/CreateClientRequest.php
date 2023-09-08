<?php

namespace App\Admin\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Module\Shared\Enum\Client\ResidencyEnum;

class CreateClientRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'type' => ['required', 'numeric'],
            'cityId' => ['required', 'numeric'],
            'currency' => ['required', 'numeric'],
            'residency' => ['required', new Enum(ResidencyEnum::class)],
            'status' => ['nullable', 'numeric'],
            'managerId' => ['nullable', 'numeric'],

            'physical' => ['required_without:legal', 'array'],
            'physical.gender' => ['nullable', 'numeric'],

            'legal' => ['required_without:physical', 'array'],
            'legal.name' => ['nullable', 'string'],
            'legal.industry' => ['nullable', 'numeric'],
            'legal.type' => ['required_with:legal', 'numeric'],
            'legal.address' => ['required_with:legal', 'string'],
            'legal.bik' => ['nullable', 'string'],
            'legal.bankCity' => ['nullable', 'string'],
            'legal.inn' => ['nullable', 'string'],
            'legal.okpoCode' => ['nullable', 'string'],
            'legal.corrAccount' => ['nullable', 'string'],
            'legal.kpp' => ['nullable', 'string'],
            'legal.bankName' => ['nullable', 'string'],
            'legal.currentAccount' => ['nullable', 'string'],
        ];
    }

    public function getName(): string
    {
        return $this->post('name');
    }

    public function getType(): int
    {
        return $this->post('type');
    }

    public function getCityId(): int
    {
        return $this->post('cityId');
    }

    public function getCurrencyId(): int
    {
        return $this->post('currency');
    }

    public function getResidency(): int
    {
        return $this->post('residency');
    }

    public function getStatus(): ?int
    {
        return $this->post('status');
    }

    public function getManagerId(): ?int
    {
        return $this->post('managerId');
    }

    public function getPhysical(): ?PhysicalDto
    {
        $data = $this->post('physical');
        if ($data === null) {
            return null;
        }

        return PhysicalDto::from($data);
    }

    public function getLegal(): ?LegalDto
    {
        $data = $this->post('legal');
        if ($data === null) {
            return null;
        }

        return LegalDto::from($data);
    }
}
