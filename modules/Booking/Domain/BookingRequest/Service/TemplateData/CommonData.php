<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData;

use Module\Booking\Domain\BookingRequest\Service\Dto\BookingDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\ManagerDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;

class CommonData implements TemplateDataInterface
{
    public function __construct(
        private readonly BookingDto $booking,
        private readonly CompanyRequisitesDto $companyRequisites,
        private readonly ManagerDto $managerDto

    ) {}

    public function toArray(): array
    {
        return [
            'booking' => $this->booking,
            'company' => $this->companyRequisites,
            'manager' => $this->managerDto,
        ];
    }
}
