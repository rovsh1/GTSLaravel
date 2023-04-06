<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasCoordinates
{
    abstract protected function getLatitudeField(): string;

    abstract protected function getLongitudeField(): string;

    public function getFillable(): array
    {
        //@hack без этого не заполняет координаты через аксессор
        return array_merge(['coordinates'], parent::getFillable());
    }

    public function coordinates(): Attribute
    {
        $coordinatesSeparator = env('COORDINATES_SEPARATOR');
        return Attribute::make(
            get: function (mixed $value, array $attributes) use ($coordinatesSeparator) {
                $latitude = $attributes[$this->getLatitudeField()] ?? null;
                $longitude = $attributes[$this->getLongitudeField()] ?? null;
                if (empty($latitude) || empty($longitude)) {
                    return null;
                }
                return "{$latitude}{$coordinatesSeparator}{$longitude}";
            },
            set: function (string $value) use ($coordinatesSeparator) {
                [$latitude, $longitude] = explode($coordinatesSeparator, $value);

                return [
                    $this->getLatitudeField() => trim($latitude),
                    $this->getLongitudeField() => trim($longitude),
                ];
            }
        );
    }

}
