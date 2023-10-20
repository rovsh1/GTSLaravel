<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Factory;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Service\StatusTranslatorInterface;
use Module\Booking\Domain\BookingRequest\Service\Dto\BookingDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\ManagerDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\CommonData;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;

class CommonDataFactory
{
    public function __construct(
        private readonly CompanyRequisitesInterface $companyRequisites,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusTranslatorInterface $statusTranslator
    ) {}


    public function build(Booking $booking): TemplateDataInterface
    {
        return new CommonData(
            $this->buildBookingDto($booking),
            $this->getCompanyRequisites(),
            $this->buildManagerDto($booking),
        );
    }

    private function buildManagerDto(Booking $booking): ManagerDto
    {
        $managerDto = $this->administratorAdapter->getManagerByBookingId($booking->id()->value());

        return new ManagerDto(
            $managerDto->name ?? $managerDto?->presentation,
            $managerDto?->email,
            $managerDto?->phone,
        );
    }

    private function buildBookingDto(Booking $booking): BookingDto
    {
        return new BookingDto(
            number: $booking->id()->value(),
            status: $this->statusTranslator->getName($booking->status()),
            createdAt: $booking->timestamps()->createdDate()->format('d.m.Y H:i:s'),
            updatedAt: $booking->timestamps()->updatedDate()->format('d.m.Y H:i:s'),
        );
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
