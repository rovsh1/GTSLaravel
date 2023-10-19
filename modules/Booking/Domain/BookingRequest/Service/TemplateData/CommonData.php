<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\BookingRequest\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\ManagerDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;

class CommonData implements TemplateDataInterface
{
    public function __construct(
        private readonly BookingId $bookingId,
        private readonly CompanyRequisitesInterface $companyRequisites,
        private readonly AdministratorAdapterInterface $administratorAdapter,
    ) {}

    public function toArray(): array
    {
        $managerDto = $this->administratorAdapter->getManagerByBookingId($this->bookingId->value());
        $managerDto = new ManagerDto(
            $managerDto->name ?? $managerDto?->presentation,
            $managerDto?->email,
            $managerDto?->phone,
        );

        return [
            'company' => $this->getCompanyRequisites(),
            'manager' => $managerDto,
        ];
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
