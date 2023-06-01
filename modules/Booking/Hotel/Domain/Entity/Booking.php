<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Module\Booking\Common\Domain\Entity\Booking as Common;
use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Entity\CancelRequestableInterface;
use Module\Booking\Common\Domain\Entity\ChangeRequestableInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\CancellationRequestGenerator;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\ChangeRequestGenerator;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\ReservationRequestGenerator;

final class Booking extends Common implements
    BookingRequestableInterface,
    ChangeRequestableInterface,
    CancelRequestableInterface
{
    public function getBookingRequestDocumentGenerator(): DocumentGeneratorInterface
    {
        return new ReservationRequestGenerator();
    }

    public function getCancelRequestDocumentGenerator(): DocumentGeneratorInterface
    {
        return new CancellationRequestGenerator();
    }

    public function getChangeRequestDocumentGenerator(): DocumentGeneratorInterface
    {
        return new ChangeRequestGenerator();
    }
}
