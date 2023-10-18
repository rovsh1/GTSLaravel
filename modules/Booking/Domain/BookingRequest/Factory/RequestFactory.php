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
        //Может быть только один актуальный запрос
        //TODO зачем тип?
        $this->requestRepository->archiveByBooking($booking->id(), $requestType);

        $fileDto = $this->fileStorageAdapter->create(
            $this->buildFilename($booking, $requestType),
            $this->generateContent($booking, $requestType)
        );

        return $this->requestRepository->create($booking->id(), $requestType, new File($fileDto->guid));
    }

    private function generateContent(Booking $booking, RequestTypeEnum $requestType): string
    {
        $templateData = $this->templateDataFactory->build($booking, $requestType);

        return $this->templateCompiler->compile(
            $this->getTemplateFromType($booking->serviceType(), $requestType),
            array_merge(
                ['company' => $this->getCompanyRequisites()],
                $templateData->toArray(),
            )
        );
    }

    private function buildFilename(Booking $booking, RequestTypeEnum $requestType): string
    {
        return $booking->id() . '-' . strtolower($requestType->name) . '-' . date('Ymd') . '.pdf';
    }

    private function getTemplateFromType(ServiceTypeEnum $serviceType, RequestTypeEnum $requestType): string
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
