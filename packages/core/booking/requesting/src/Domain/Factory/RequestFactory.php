<?php

namespace Pkg\Booking\Requesting\Domain\Factory;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Shared\Service\TemplateCompilerInterface;
use Pkg\Booking\Requesting\Domain\Entity\BookingRequest;
use Pkg\Booking\Requesting\Domain\Event\BookingRequestSent;
use Pkg\Booking\Requesting\Domain\Event\CancelRequestSent;
use Pkg\Booking\Requesting\Domain\Event\ChangeRequestSent;
use Pkg\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Pkg\Booking\Requesting\Domain\Service\TemplateDataFactory;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\ValueObject\File;

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
            RequestTypeEnum::BOOKING => new BookingRequestSent($booking, $request),
            RequestTypeEnum::CHANGE => new ChangeRequestSent($booking, $request),
            RequestTypeEnum::CANCEL => new CancelRequestSent($booking, $request),
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
        $name = '';
        if ($serviceType === ServiceTypeEnum::HOTEL_BOOKING) {
            $name .= 'hotel';
        } elseif ($serviceType->isAirportService()) {
            $name .= 'airport';
        } elseif ($serviceType->isTransferService()) {
            $name .= 'transfer';
        } else {
            $name .= 'other';
        }

        $name .= '.';
        $name .= match ($requestType) {
            RequestTypeEnum::BOOKING => 'booking',
            RequestTypeEnum::CHANGE => 'change',
            RequestTypeEnum::CANCEL => 'cancel'
        };
        $name .= '_request';

        return "BookingRequesting::$name";
    }
}
