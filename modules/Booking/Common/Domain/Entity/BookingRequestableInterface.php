<?php

namespace Module\Booking\Common\Domain\Entity;

use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;

interface BookingRequestableInterface extends ReservationInterface
{
    public function getBookingRequestDocumentGenerator(): DocumentGeneratorInterface;
}
