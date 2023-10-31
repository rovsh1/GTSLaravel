<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Client;

use Sdk\Module\Foundation\Support\Dto\Dto;

class LegalDto extends Dto
{
    public function __construct(
        public readonly string $name,
        public readonly ?int $industry,
        public readonly string $address,
        public readonly ?string $bik,
        public readonly ?string $bankCity,
        public readonly ?string $inn,
        public readonly ?string $okpoCode,
        public readonly ?string $corrAccount,
        public readonly ?string $kpp,
        public readonly ?string $bankName,
        public readonly ?string $currentAccount,
    ) {
    }
}
