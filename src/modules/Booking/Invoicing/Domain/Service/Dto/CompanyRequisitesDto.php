<?php

namespace Module\Booking\Invoicing\Domain\Service\Dto;

final class CompanyRequisitesDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly string $email,
        public readonly string $legalAddress,
        public readonly string $signer,
        public readonly string $region,
    ) {
    }
}
