<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Factory;

use Module\Booking\Deprecated\AirportBooking\Service\DocumentGenerator\CancellationRequestGenerator as AirportCancellationRequestGenerator;
use Module\Booking\Deprecated\AirportBooking\Service\DocumentGenerator\ChangeRequestGenerator as AirportChangeRequestGenerator;
use Module\Booking\Deprecated\AirportBooking\Service\DocumentGenerator\ReservationRequestGenerator as AirportReservationRequestGenerator;
use Module\Booking\Deprecated\HotelBooking\Service\DocumentGenerator\CancellationRequestGenerator;
use Module\Booking\Deprecated\HotelBooking\Service\DocumentGenerator\ChangeRequestGenerator;
use Module\Booking\Deprecated\HotelBooking\Service\DocumentGenerator\ReservationRequestGenerator;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Domain\Shared\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Domain\Shared\Exception\BookingTypeDoesntHaveDocumentGenerator;
use Module\Booking\Domain\Shared\Service\DocumentGenerator\RequestGeneratorInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Sdk\Module\Contracts\ModuleInterface;

class DocumentGeneratorFactory
{
    public function __construct(
        private readonly ModuleInterface $module
    ) {
    }

    public function getRequestGenerator(
        RequestTypeEnum $requestType,
        BookingRequestableInterface $booking
    ): RequestGeneratorInterface {
        return match ($requestType) {
            RequestTypeEnum::BOOKING => $this->getBookingDocumentGenerator($booking),
            RequestTypeEnum::CHANGE => $this->getChangeDocumentGenerator($booking),
            RequestTypeEnum::CANCEL => $this->getCancelDocumentGenerator($booking),
            default => throw new \InvalidArgumentException('Unknown request')
        };
    }

    private function getCancelDocumentGenerator(BookingRequestableInterface $booking): RequestGeneratorInterface
    {
        return match ($booking->type()) {
            //@todo прокинуть зависимости
            BookingTypeEnum::HOTEL => $this->module->make(CancellationRequestGenerator::class),
            BookingTypeEnum::AIRPORT => $this->module->make(AirportCancellationRequestGenerator::class),
//            BookingTypeEnum::HOTEL => new CancellationRequestGenerator(
//                $this->module->get(HotelAdapterInterface::class),
//                $this->module->get(AdministratorAdapterInterface::class),
//                $this->module->get(StatusStorage::class),
//                $this->module->get(GuestRepositoryInterface::class),
//            ),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    private function getChangeDocumentGenerator(BookingRequestableInterface $booking): RequestGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => $this->module->make(ChangeRequestGenerator::class),
            BookingTypeEnum::AIRPORT => $this->module->make(AirportChangeRequestGenerator::class),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    private function getBookingDocumentGenerator(BookingRequestableInterface $booking): RequestGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => $this->module->make(ReservationRequestGenerator::class),
            BookingTypeEnum::AIRPORT => $this->module->make(AirportReservationRequestGenerator::class),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }
}
