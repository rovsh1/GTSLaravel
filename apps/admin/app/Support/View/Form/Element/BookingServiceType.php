<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Support\Facades\Format;
use Module\Shared\Enum\ServiceTypeEnum;

class BookingServiceType extends Enum
{
    protected array $options = [
        'enum' => ServiceTypeEnum::class,
        'withoutHotel' => false,
    ];

    protected function getItems(): array
    {
        $cases = $this->enum::cases();
        if ($this->options['withoutHotel']) {
            $cases = array_filter(
                $cases,
                fn(ServiceTypeEnum $serviceType) => $serviceType !== ServiceTypeEnum::HOTEL_BOOKING
            );
        }

        return array_map(function ($case): array {
            return [
                'value' => $case->value ?? $case->name,
                'text' => Format::enum($case)
            ];
        }, $cases);
    }
}
