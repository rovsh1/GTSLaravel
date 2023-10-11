<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\ServiceProvider;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @deprecated
 */
class DeprecatedSearchServicesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city_id' => ['required', 'numeric'],
        ];
    }

    public function getCityId(): int
    {
        return (int)$this->get('city_id');
    }
}
