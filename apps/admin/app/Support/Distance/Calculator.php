<?php

namespace App\Admin\Support\Distance;

class Calculator
{
    /**
     * @param Point $p1
     * @param Point $p2
     * @param string $unit
     * @return int
     */
    public function getDistance(Point $p1, Point $p2, DistanceUnitEnum $unit = DistanceUnitEnum::Meters): int
    {
        $latitudeFrom = $p1->latitude;
        $longitudeFrom = $p1->longitude;
        $latitudeTo = $p2->latitude;
        $longitudeTo = $p2->longitude;
        $theta = $longitudeFrom - $longitudeTo;
        $distance = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) + cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $miles = $distance * 60 * 1.1515;

        switch ($unit) {
            case DistanceUnitEnum::Kilometers:
                return round($miles * 1.609344, 2);
            case DistanceUnitEnum::Meters:
                return round($miles * 1609.344);
            //miles
            default:
                return round($miles, 2);
        }
    }
}
