<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Module\Shared\Enum\ServiceTypeEnum;

class SearchServicesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(ServiceTypeEnum::class)],
            'city_id' => ['nullable', 'numeric'],
            'supplier_id' => ['nullable', 'numeric'],
            'only_with_contract' => ['nullable', 'boolean']
        ];
    }

    public function getType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::from($this->integer('type'));
    }

    public function getCityId(): ?int
    {
        return $this->integer('city_id', null);
    }

    public function getSupplierId(): ?int
    {
        return $this->integer('supplier_id', null);
    }

    public function onlyWithContract(): bool
    {
        $onlyWithContract = $this->boolean('only_with_contract');

        return is_bool($onlyWithContract) ? $onlyWithContract : (bool)$onlyWithContract;
    }
}
