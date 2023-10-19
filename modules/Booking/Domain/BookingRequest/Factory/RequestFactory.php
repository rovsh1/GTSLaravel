<?php

namespace Module\Booking\Domain\BookingRequest\Factory;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\BookingRequest\BookingRequest;
use Module\Booking\Domain\BookingRequest\Event\BookingRequestSent;
use Module\Booking\Domain\BookingRequest\Event\CancelRequestSent;
use Module\Booking\Domain\BookingRequest\Event\ChangeRequestSent;
use Module\Booking\Domain\BookingRequest\Repository\RequestRepositoryInterface;
use Module\Booking\Domain\BookingRequest\Service\TemplateCompilerInterface;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataFactory;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\ValueObject\File;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class RequestFactory
{
    public function __construct(
        private readonly TemplateCompilerInterface $templateCompiler,
        private readonly TemplateDataFactory $templateDataFactory,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function generate(Booking $booking, RequestTypeEnum $requestType): BookingRequest
    {
        $this->requestRepository->archiveByBooking($booking->id());

        $fileDto = $this->fileStorageAdapter->create(
            $this->getFilename($booking, $requestType),
            $this->generateContent($booking, $requestType)
        );

        $request = $this->requestRepository->create($booking->id(), $requestType, new File($fileDto->guid));
        $this->dispatchEvent($request, $booking);

        return $request;
    }

    private function dispatchEvent(BookingRequest $request, Booking $booking): void
    {
        $event = match ($request->type()) {
            RequestTypeEnum::BOOKING => new BookingRequestSent($booking, $request->id()),
            RequestTypeEnum::CHANGE => new ChangeRequestSent($booking, $request->id()),
            RequestTypeEnum::CANCEL => new CancelRequestSent($booking, $request->id()),
        };
        $this->eventDispatcher->dispatch($event);
    }

    private function generateContent(Booking $booking, RequestTypeEnum $requestType): string
    {
        $commonData = $this->templateDataFactory->buildCommon($booking);
        $templateData = $this->templateDataFactory->build($booking, $requestType);

        return $this->templateCompiler->compile(
            $this->getTemplateName($booking->serviceType(), $requestType),
            [
                ...$commonData->toArray(),
                ...$templateData->toArray(),
            ]
        );
    }

    private function getFilename(Booking $booking, RequestTypeEnum $requestType): string
    {
        return $booking->id() . '-' . strtolower($requestType->name) . '-' . date('Ymd') . '.pdf';
    }

    private function getTemplateName(ServiceTypeEnum $serviceType, RequestTypeEnum $requestType): string
    {
        if ($serviceType === ServiceTypeEnum::HOTEL_BOOKING) {
            $name = 'hotel';
        } elseif ($serviceType->isAirportService()) {
            $name = 'airport';
        } elseif ($serviceType->isTransferService()) {
            $name = 'transfer';
        } else {
            $name = 'other';
        }

        $name .= '.';
        $name .= match ($requestType) {
            RequestTypeEnum::BOOKING => 'booking',
            RequestTypeEnum::CHANGE => 'change',
            RequestTypeEnum::CANCEL => 'cancel'
        };
        $name .= '_request';

        return $name;
    }
}
