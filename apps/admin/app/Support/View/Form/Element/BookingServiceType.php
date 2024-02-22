<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Support\Facades\Format;
use Sdk\Shared\Enum\ServiceTypeEnum;

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
            $cases = $this->enum::getWithoutHotel();
        }

        return array_map(function ($case): array {
            return [
                'value' => $case->value ?? $case->name,
                'text' => Format::enum($case)
            ];
        }, $cases);
    }
}
