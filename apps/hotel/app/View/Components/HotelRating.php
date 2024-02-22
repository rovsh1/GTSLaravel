<?php

namespace App\Hotel\View\Components;

use Illuminate\View\Component;
use Sdk\Shared\Enum\Hotel\RatingEnum;

class HotelRating extends Component
{
    public function __construct(public readonly int|RatingEnum|null $value) {}

    public function render(): string
    {
        $value = $this->value?->value ?? $this->value;
        if (!$value) {
            return '';
        }

        return '<span class="rating rating-' . $value . '">'
            . $value . ' '
            . self::stars($value)
            . '</span>';
    }

    private static function stars(?int $rating): string
    {
        $n = '';
        for ($j = 1; $j <= $rating; $j++) {
            $n .= '<i class="icon">star</i>';
        }
        return $n;
    }
}
