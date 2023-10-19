<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData;

use Module\Booking\Application\Admin\Shared\Service\StatusStorage;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\BookingRequest\Service\Dto\BookingDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\ManagerDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;

class CommonData implements TemplateDataInterface
{
    public function __construct(
        private readonly Booking $booking,
        private readonly CompanyRequisitesInterface $companyRequisites,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusStorage $statusStorage,
    ) {}

    public function toArray(): array
    {
        $managerDto = $this->administratorAdapter->getManagerByBookingId($this->booking->id()->value());
        $managerDto = new ManagerDto(
            $managerDto->name ?? $managerDto?->presentation,
            $managerDto?->email,
            $managerDto?->phone,
        );

        return [
            'booking' => $this->buildBookingDto(),
            'company' => $this->getCompanyRequisites(),
            'manager' => $managerDto,
        ];
    }

    private function buildBookingDto(): BookingDto
    {
        $booking = $this->booking;

        return new BookingDto(
            number: $booking->id()->value(),
            status: $this->statusStorage->get($booking->status())->name,
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
