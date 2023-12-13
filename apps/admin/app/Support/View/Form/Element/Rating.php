<?php

declare(strict_types=1);

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Select;
use Sdk\Shared\Enum\Hotel\RatingEnum;

class Rating extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(
            $this->getItems()
        );
    }

    protected function prepareValue($value)
    {
        if (is_object($value)) {
            $value = $value->value;
        }

        return parent::prepareValue($value);
    }

    private function getItems(): array
    {
        return array_map(fn(RatingEnum $rating): array => [
            'value' => $rating->value,
            'text' => $this->getStars($rating)
        ], RatingEnum::cases());
    }

    private function getStars(RatingEnum $rating): string
    {
        $n = '';
        for ($j = 1; $j <= $rating->value; $j++) {
            $n .= '✯ ';
        }

        return trim($n);
    }
}
