<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Service\TemplateData;

use Pkg\Booking\Requesting\Domain\Service\Dto\BookingDto;
use Pkg\Booking\Requesting\Domain\Service\Dto\ClientDto;
use Pkg\Booking\Requesting\Domain\Service\Dto\CompanyRequisitesDto;
use Pkg\Booking\Requesting\Domain\Service\Dto\ManagerDto;
use Pkg\Booking\Requesting\Domain\Service\TemplateDataInterface;

class CommonData implements TemplateDataInterface
{
    public function __construct(
        private readonly BookingDto $booking,
        private readonly CompanyRequisitesDto $companyRequisites,
        private readonly ManagerDto $managerDto,
        private readonly ClientDto $clientDto,
    ) {}

    public function toArray(): array
    {
        return [
            'booking' => $this->booking,
            'company' => $this->companyRequisites,
            'manager' => $this->managerDto,
            'client' => $this->clientDto
        ];
    }
}
