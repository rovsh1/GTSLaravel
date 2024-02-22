<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData;

use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\BookingDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\ClientDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\CompanyRequisitesDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\ManagerDto;

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
