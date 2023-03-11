<?php

namespace App\Admin\Support\View\Components;

use Illuminate\View\Component;

class HotelRating extends Component
{
    public function __construct(public readonly ?int $value) {}

    public function render(): string
    {
        if (!$this->value) {
            return '';
        }

        return '<span class="rating rating-' . $this->value . '">'
            . $this->value . ' '
            . self::stars($this->value)
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
