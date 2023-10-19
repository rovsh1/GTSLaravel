<?php

namespace Module\Booking\Domain\BookingRequest\Factory;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\BookingRequest\BookingRequest;
use Module\Booking\Domain\BookingRequest\Repository\RequestRepositoryInterface;
use Module\Booking\Domain\BookingRequest\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateCompilerInterface;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataFactory;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\ValueObject\File;

class RequestFactory
{
    public function __construct(
        protected readonly CompanyRequisitesInterface $companyRequisites,
        protected readonly TemplateCompilerInterface $templateCompiler,
        protected readonly TemplateDataFactory $templateDataFactory,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
    ) {
    }

    public function generate(Booking $booking, RequestTypeEnum $requestType): BookingRequest
    {
        $this->requestRepository->archiveByBooking($booking->id());

        $fileDto = $this->fileStorageAdapter->create(
            $this->getFilename($booking, $requestType),
            $this->generateContent($booking, $requestType)
        );

        return $this->requestRepository->create($booking->id(), $requestType, new File($fileDto->guid));
    }

    private function generateContent(Booking $booking, RequestTypeEnum $requestType): string
    {
        $templateData = $this->templateDataFactory->build($booking, $requestType);

        $bookingDto = null;

        return $this->templateCompiler->compile(
            $this->getTemplateName($booking->serviceType(), $requestType),
            array_merge([
                'company' => $this->getCompanyRequisites(),
                'booking' => $bookingDto
            ], $templateData->toArray())
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

        $name .= '-';
        $name .= match ($requestType) {
            RequestTypeEnum::BOOKING => 'booking',
            RequestTypeEnum::CHANGE => 'change',
            RequestTypeEnum::CANCEL => 'cancel'
        };

        return $name;
    }

    private function getCompanyRequisites(): CompanyRequisitesDto
    {
        return new CompanyRequisitesDto(
            name: $this->companyRequisites->name(),
            phone: $this->companyRequisites->phone(),
            email: $this->companyRequisites->email(),
            legalAddress: $this->companyRequisites->legalAddress(),
            signer: $this->companyRequisites->signer(),
            region: $this->companyRequisites->region(),
        );
    }
}
