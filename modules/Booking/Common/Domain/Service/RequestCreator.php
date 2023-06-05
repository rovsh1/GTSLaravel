<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;
use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;

class RequestCreator
{
    public function __construct(
        private readonly DocumentGeneratorFactory $documentGeneratorFactory,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
    ) {}

    public function create(Booking $booking, RequestRules $rules): Request
    {
        $requestType = RequestTypeEnum::CHANGE;
        if ($rules->canSendBookingRequest($booking->status())) {
            $requestType = RequestTypeEnum::BOOKING;
        }
        if ($rules->canSendCancellationRequest($booking->status())) {
            $requestType = RequestTypeEnum::CANCEL;
        }
        $request = $this->requestRepository->create($booking->id(), $requestType);
        $documentGenerator = $this->getDocumentGenerator($rules, $booking);
        $this->generateFile($documentGenerator, $booking, $request->id());
        return $request;
    }

    private function getDocumentGenerator(
        RequestRules $rules,
        BookingRequestableInterface $booking
    ): DocumentGeneratorInterface {
        $documentGenerator = $this->documentGeneratorFactory->getChangeDocumentGenerator($booking);
        if ($rules->canSendBookingRequest($booking->status())) {
            $documentGenerator = $this->documentGeneratorFactory->getBookingDocumentGenerator($booking);
        }
        if ($rules->canSendCancellationRequest($booking->status())) {
            $documentGenerator = $this->documentGeneratorFactory->getCancelDocumentGenerator($booking);
        }
        return $documentGenerator;
    }

    private function generateFile(DocumentGeneratorInterface $documentGenerator, Booking $booking, int $requestId): void
    {
        $documentContent = $documentGenerator->generate($booking);
        $this->fileStorageAdapter->create(
            Request::class,
            $requestId,
            \Str::random(18) . '.pdf',
            $documentContent
        );
    }
}
