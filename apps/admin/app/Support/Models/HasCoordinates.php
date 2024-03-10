<?php

namespace App\Admin\Support\Models;

use App\Admin\Support\Distance\Point;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasCoordinates
{
    private string $coordinatesSeparator = ',';

    abstract protected function getLatitudeField(): string;

    abstract protected function getLongitudeField(): string;

    public function getFillable(): array
    {
        //@hack без этого не заполняет координаты через аксессор
        return array_merge(['coordinates'], parent::getFillable());
    }

    public function coordinates(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $latitude = $attributes[$this->getLatitudeField()] ?? null;
                $longitude = $attributes[$this->getLongitudeField()] ?? null;
                if (empty($latitude) || empty($longitude)) {
                    return null;
                }

                return "{$latitude}{$this->coordinatesSeparator} {$longitude}";
            },
            set: function (string $value) {
                $point = Point::buildFromCoordinates($value);

                return [
                    $this->getLatitudeField() => $point->latitude,
                    $this->getLongitudeField() => $point->longitude,
                ];
            }
        );
    }

}
