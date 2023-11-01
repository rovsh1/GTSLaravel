<?php

declare(strict_types=1);

namespace Module\Client\Application\Admin\Request;

class SetLegalBankRequisitesDto
{
    public function __construct(
        public readonly int $legalId,
        public readonly string $bik,
        public readonly string $inn,
        public readonly string $okpo,
        public readonly string $correspondentAccount,
        public readonly string $kpp,
        public readonly string $bankName,
        public readonly string $currentAccount,
        public readonly string $cityName
    ) {}
}
