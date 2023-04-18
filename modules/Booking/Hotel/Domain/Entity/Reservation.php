<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\Entity\ReservationItemInterface;
use Module\Booking\Common\Domain\Entity\ReservationRequestableInterface;
use Module\Booking\Hotel\Domain\ValueObject;
use Module\Shared\Domain\Entity\EntityInterface;

class Reservation implements EntityInterface, ReservationItemInterface, ReservationRequestableInterface
{
    public function __construct(
        private readonly ValueObject\ReservationIdentifier $identifier,
        //private readonly Manager $author,
        private readonly ValueObject\ReservationStatus     $status,
        private readonly ValueObject\Client                $client,
        private readonly ValueObject\Hotel                 $hotel,
        private readonly ValueObject\ReservationPeriod     $reservationPeriod,
        private readonly ValueObject\ReservationDetails    $details,
        //private readonly ValueObject\Price $price,
        private readonly CarbonInterface                   $createdDate,
    ) {}

    public function identifier(): ValueObject\ReservationIdentifier
    {
        return $this->identifier;
    }

    public function status(): ValueObject\ReservationStatus
    {
        return $this->status;
    }

    public function client(): ValueObject\Client
    {
        return $this->client;
    }

    public function hotel(): ValueObject\Hotel
    {
        return $this->hotel;
    }

    public function period(): ValueObject\ReservationPeriod
    {
        return $this->reservationPeriod;
    }

    public function details(): ValueObject\ReservationDetails
    {
        return $this->details;
    }

//    public function price(): ValueObject\Price
//    {
//        return $this->price;
//    }

    public function id(): int
    {
        return $this->identifier->id();
    }

    public function number(): string
    {
        return $this->identifier->number();
    }

    public function dateFrom(): \DateTime
    {
        return $this->reservationPeriod->dateFrom();
    }

    public function dateTo(): \DateTime
    {
        return $this->reservationPeriod->dateTo();
    }

    public function createdDate(): CarbonInterface
    {
        return $this->createdDate;
    }
}
