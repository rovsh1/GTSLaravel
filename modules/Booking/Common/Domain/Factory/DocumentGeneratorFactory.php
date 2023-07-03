<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Factory;

use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Common\Domain\Exception\BookingTypeDoesntHaveDocumentGenerator;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\CancellationRequestGenerator;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\ChangeRequestGenerator;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\ReservationRequestGenerator;
use Sdk\Module\Contracts\ModuleInterface;

class DocumentGeneratorFactory
{
    public function __construct(
        private readonly string $templatesPath,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
        private readonly ModuleInterface $module
    ) {
    }

    public function getGenerator(Request $request, BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($request->type()) {
            RequestTypeEnum::BOOKING => $this->getBookingDocumentGenerator($booking),
            RequestTypeEnum::CHANGE => $this->getChangeDocumentGenerator($booking),
            RequestTypeEnum::CANCEL => $this->getCancelDocumentGenerator($booking),
            default => throw new \InvalidArgumentException('Unknown request')
        };
    }

    private function getCancelDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            //@todo прокинуть зависимости
            BookingTypeEnum::HOTEL => new CancellationRequestGenerator($this->templatesPath, $this->fileStorageAdapter),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    private function getChangeDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new ChangeRequestGenerator($this->templatesPath, $this->fileStorageAdapter),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    private function getBookingDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new ReservationRequestGenerator(
                $this->templatesPath,
                $this->fileStorageAdapter,
                $this->module->get(HotelAdapterInterface::class)
            ),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }
}
