<?php

namespace Module\Reservation\HotelReservation\Domain\Entity;

use GTS\Reservation\HotelReservation\Domain\ValueObject;
use Module\Reservation\Common\Domain\Entity\ReservationItemInterface;
use Module\Reservation\Common\Domain\Entity\ReservationRequestableInterface;

class Reservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function __construct(
        private readonly \Module\Reservation\HotelReservation\Domain\ValueObject\ReservationIdentifier $identifier,
        //private readonly Manager $author,
        private readonly \Module\Reservation\HotelReservation\Domain\ValueObject\ReservationStatus $status,
        private readonly \Module\Reservation\HotelReservation\Domain\ValueObject\Client $client,
        private readonly \Module\Reservation\HotelReservation\Domain\ValueObject\Hotel $hotel,
        private readonly \Module\Reservation\HotelReservation\Domain\ValueObject\ReservationPeriod $reservationPeriod,
        private readonly \Module\Reservation\HotelReservation\Domain\ValueObject\ReservationDetails $details,
        //private readonly ValueObject\Price $price,
    ) {}

    public function identifier(): \Module\Reservation\HotelReservation\Domain\ValueObject\ReservationIdentifier
    {
        return $this->identifier;
    }

    public function status(): \Module\Reservation\HotelReservation\Domain\ValueObject\ReservationStatus
    {
        return $this->status;
    }

    public function client(): \Module\Reservation\HotelReservation\Domain\ValueObject\Client
    {
        return $this->client;
    }

    public function hotel(): \Module\Reservation\HotelReservation\Domain\ValueObject\Hotel
    {
        return $this->hotel;
    }

    public function period(): \Module\Reservation\HotelReservation\Domain\ValueObject\ReservationPeriod
    {
        return $this->reservationPeriod;
    }

    public function details(): \Module\Reservation\HotelReservation\Domain\ValueObject\ReservationDetails
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
