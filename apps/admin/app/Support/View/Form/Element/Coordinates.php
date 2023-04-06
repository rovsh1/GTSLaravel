<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Text;

class Coordinates extends Text
{
    protected array $options = [
        'latitudeField' => 'address_lat',
        'longitudeField' => 'address_lon',
    ];

    protected function prepareValue($value)
    {
        $coordinatesSeparator = env('COORDINATES_SEPARATOR');
        $coordinatesParts = explode($coordinatesSeparator, $value);
        $latitude = null;
        $longitude = null;
        if (count($coordinatesParts) === 2) {
            $latitude = $coordinatesParts[0];
            $longitude = $coordinatesParts[1];
        }
        return [
            $this->latitudeField => $latitude,
            $this->longitudeField => $longitude,
        ];
    }
}
