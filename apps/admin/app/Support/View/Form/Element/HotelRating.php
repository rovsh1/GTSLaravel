<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Select;

class HotelRating extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(
            items: $this->getItems()
        );
    }

    private function getItems(): array
    {
        $ratingItems = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingItems[] = ['id' => $i, 'name' => $this->getRatingLabel($i)];
        }
        return $ratingItems;
    }

    private function getRatingLabel(?int $rating = null): string
    {
        return $rating . ' ' . $this->getRatingStars($rating);
    }

    private function getRatingStars(?int $rating = null): string
    {
        if ($rating === null) {
            return '';
        }
        if ($rating <= 1) {
            return "✯";
        }
        return '✯' . $this->getRatingStars($rating - 1);
    }
}
