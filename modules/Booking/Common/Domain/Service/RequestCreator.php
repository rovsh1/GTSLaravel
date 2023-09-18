<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;
use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\ValueObject\File;

class RequestCreator
{
    public function __construct(
        private readonly DocumentGeneratorFactory $documentGeneratorFactory,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
    ) {
    }

    public function create(BookingRequestableInterface $booking, RequestRules $rules): Request
    {
        $requestType = $rules->getRequestTypeByStatus($booking->status());

        $this->requestRepository->archiveByBooking($booking->id(), $requestType);

        $documentGenerator = $this->documentGeneratorFactory->getRequestGenerator($requestType, $booking);
        $documentContent = $documentGenerator->generate($booking);

        $fileDto = $this->fileStorageAdapter->create(
            $this->getFilename($requestType, $booking->id()->value()),
            $documentContent
        );

        return $this->requestRepository->create($booking->id(), $requestType, new File($fileDto->guid));
    }

    public function getFilename(RequestTypeEnum $requestType, int $bookingId): string
    {
        return match ($requestType) {
                RequestTypeEnum::BOOKING => "new_booking_$bookingId",
                RequestTypeEnum::CHANGE => "change_booking_$bookingId",
                RequestTypeEnum::CANCEL => "cancel_booking_$bookingId",
            } . '.pdf';
    }
}
