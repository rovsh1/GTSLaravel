<?php

namespace Module\HotelOld\Domain\Service\PriceCalculator;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\HotelOld\Domain\Entity\PriceRate;
use Module\HotelOld\Domain\Entity\Room;
use Module\HotelOld\Domain\Entity\Season;

class PriceCalculator
{
//    private Season $season;

    private PriceRate $rate;

    private Room $room;

    private int $guestsNumber;

    private $residentType;

    private $legalType;

    public function __construct() {}

//    public function season(Season $season): static
//    {
//        $this->season = $season;
//        return $this;
//    }

    public function rate(PriceRate $rate): static
    {
        $this->rate = $rate;
        return $this;
    }

    public function room(Room $room): static
    {
        $this->room = $room;
        return $this;
    }

    public function guestsNumber(int $guestsNumber): static
    {
        $this->guestsNumber = $guestsNumber;
        return $this;
    }

    public function residentType($residentType): static
    {
        $this->residentType = $residentType;
        return $this;
    }

    public function legalType($legalType): static
    {
        $this->legalType = $legalType;
        return $this;
    }

    public function calculateDate(CarbonInterface $date): void {}

    public function calculatePeriod(CarbonPeriod $period): void {}

    public function calculateSeason(Season $season): void
    {
        $this->calculatePeriod($season->period());
    }
}


