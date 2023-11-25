<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Sdk\Shared\Enum\ServiceTypeEnum;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service_type' => ['nullable', new Enum(ServiceTypeEnum::class)],
        ];
    }

    public function getServiceType(): ?ServiceTypeEnum
    {
        return ServiceTypeEnum::tryFrom((int)$this->get('service_type'));
    }
}
