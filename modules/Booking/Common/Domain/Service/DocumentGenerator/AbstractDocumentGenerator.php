<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Shared\Domain\Service\CompanyRequisitesInterface;

abstract class AbstractDocumentGenerator
{
    public function __construct(
        protected readonly CompanyRequisitesInterface $companyRequisites
    ) {
    }

    protected function getCompanyAttributes(): array
    {
        return [
            'company' => $this->companyRequisites->name(),
            'phone' => $this->companyRequisites->phone(),
            'email' => $this->companyRequisites->email(),
            'address' => $this->companyRequisites->legalAddress(),
        ];
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getBookingAttributes(BookingInterface $booking): array;
}
