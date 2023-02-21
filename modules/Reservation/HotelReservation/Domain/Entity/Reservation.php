<?php

namespace Module\Reservation\HotelReservation\Domain\Entity;

use Module\Reservation\HotelReservation\Domain\ValueObject;
use Module\Reservation\Common\Domain\Entity\ReservationItemInterface;
use Module\Reservation\Common\Domain\Entity\ReservationRequestableInterface;

class Reservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function __construct(
        private readonly ValueObject\ReservationIdentifier $identifier,
        //private readonly Manager $author,
        private readonly ValueObject\ReservationStatus $status,
        private readonly ValueObject\Client $client,
        private readonly ValueObject\Hotel $hotel,
        private readonly ValueObject\ReservationPeriod $reservationPeriod,
        private readonly ValueObject\ReservationDetails $details,
        //private readonly ValueObject\Price $price,
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
}
