<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Factory;

use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Common\Domain\Exception\BookingTypeDoesntHaveDocumentGenerator;
use Module\Booking\Common\Domain\Service\DocumentGenerator\RequestGeneratorInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Booking\HotelBooking\Domain\Service\DocumentGenerator\CancellationRequestGenerator;
use Module\Booking\HotelBooking\Domain\Service\DocumentGenerator\ChangeRequestGenerator;
use Module\Booking\HotelBooking\Domain\Service\DocumentGenerator\ReservationRequestGenerator;
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
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    private function getBookingDocumentGenerator(BookingRequestableInterface $booking): RequestGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => $this->module->make(ReservationRequestGenerator::class),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }
}
