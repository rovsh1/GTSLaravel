<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Factory;

use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Exception\BookingTypeDoesntHaveDocumentGenerator;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\CancellationRequestGenerator;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\ChangeRequestGenerator;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\ReservationRequestGenerator;

class DocumentGeneratorFactory
{
    public function __construct(private readonly string $templatesPath) {}

    public function getCancelDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new CancellationRequestGenerator($this->templatesPath),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    public function getChangeDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new ChangeRequestGenerator($this->templatesPath),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    public function getBookingDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new ReservationRequestGenerator($this->templatesPath),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }
}
