<?php

namespace App\Admin\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Sdk\Shared\Enum\Client\LanguageEnum;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\CurrencyEnum;

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
            'currency' => ['required', new Enum(CurrencyEnum::class)],
            'markupGroupId' => ['required', 'numeric'],
            'residency' => ['required', new Enum(ResidencyEnum::class)],
            'status' => ['nullable', 'numeric'],
            'language' => ['required', new Enum(LanguageEnum::class)],
            'managerId' => ['nullable', 'numeric'],

            'physical' => ['required_without:legal', 'array'],
            'physical.countryId' => ['required_without:legal', 'numeric'],
            'physical.gender' => ['nullable', 'numeric'],

            'legal' => ['required_without:physical', 'array'],
            'legal.cityId' => ['required_with:legal', 'numeric'],
            'legal.name' => ['required_with:legal', 'string'],
            'legal.industry' => ['nullable', 'numeric'],
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

    public function getLanguage(): string
    {
        return $this->post('language');
    }

    public function getCityId(): ?int
    {
        $legal = $this->post('legal');
        if (empty($legal)) {
            return null;
        }
        return (int)$legal['cityId'];
    }

    public function getCurrency(): string
    {
        return $this->post('currency');
    }

    public function getResidency(): int
    {
        return $this->post('residency');
    }

    public function getMarkupGroupId(): int
    {
        return $this->post('markupGroupId');
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

        return new PhysicalDto($data['countryId'], $data['gender']);
    }

    public function getLegal(): ?LegalDto
    {
        $data = $this->post('legal');
        if ($data === null) {
            return null;
        }

        return new LegalDto(
            name: $data['name'],
            cityId: $data['cityId'],
            industry: $data['industry'],
            address: $data['address'],
            bik: $data['bik'],
            bankCity: $data['bankCity'],
            inn: $data['inn'],
            okpoCode: $data['okpoCode'],
            corrAccount: $data['corrAccount'],
            kpp: $data['kpp'],
            bankName: $data['bankName'],
            currentAccount: $data['currentAccount'],
        );
    }
}
