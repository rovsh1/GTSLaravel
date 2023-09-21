<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;
use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\ValueObject\File;

class RequestCreator
{
    public function __construct(
        private readonly DocumentGeneratorFactory $documentGeneratorFactory,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
    ) {}

    public function create(BookingRequestableInterface $booking, RequestRules $rules): Request
    {
        $requestType = $rules->getRequestTypeByStatus($booking->status());

        $this->requestRepository->archiveByBooking($booking->id(), $requestType);

        $documentGenerator = $this->documentGeneratorFactory->getRequestGenerator($requestType, $booking);
        $documentContent = $documentGenerator->generate($booking);

        $filename = match ($booking->type()) {
            BookingTypeEnum::HOTEL => $this->getHotelFilename($requestType, $booking->id()->value()),
            BookingTypeEnum::AIRPORT => $this->getAirportFilename($requestType, $booking->id()->value()),
        };

        $fileDto = $this->fileStorageAdapter->create(
            $filename,
            $documentContent
        );

        return $this->requestRepository->create($booking->id(), $requestType, new File($fileDto->guid));
    }

    public function getHotelFilename(RequestTypeEnum $requestType, int $bookingId): string
    {
        return match ($requestType) {
                RequestTypeEnum::BOOKING => "new_booking_$bookingId",
                RequestTypeEnum::CHANGE => "change_booking_$bookingId",
                RequestTypeEnum::CANCEL => "cancel_booking_$bookingId",
            } . '.pdf';
    }

    public function getAirportFilename(RequestTypeEnum $requestType, int $bookingId): string
    {
        return match ($requestType) {
                RequestTypeEnum::BOOKING => "new_airport_booking_$bookingId",
                RequestTypeEnum::CHANGE => "change_airport_booking_$bookingId",
                RequestTypeEnum::CANCEL => "cancel_airport_booking_$bookingId",
            } . '.pdf';
    }
}
