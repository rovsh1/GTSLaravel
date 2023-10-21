<?php

namespace Module\Booking\Domain\BookingRequest\Service;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;

class DocumentGenerator
{
    public function __construct(
        protected readonly CompanyRequisitesInterface $companyRequisites,
        protected readonly TemplateCompilerInterface $templateCompiler,
        protected readonly TemplateDataFactory $templateDataFactory,
    ) {
    }

    public function generate(string $template, BookingId $bookingId): string
    {
        $templateData = $this->templateDataFactory->build($bookingId);

        return $this->templateCompiler->compile(
            $template,
            array_merge(
                ['company' => $this->getCompanyAttributes()],
                $templateData->toArray(),
            )
        );
    }

    private function getCompanyAttributes(): array
    {
        return [
            'name' => $this->companyRequisites->name(),
            'phone' => $this->companyRequisites->phone(),
            'email' => $this->companyRequisites->email(),
            'address' => $this->companyRequisites->legalAddress(),
            'signer' => $this->companyRequisites->signer(),
            'cityAndCountry' => $this->companyRequisites->region(),
        ];
    }
}
