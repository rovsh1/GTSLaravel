<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\Dto;

use Sdk\Shared\Enum\Client\LanguageEnum;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\Client\TypeEnum;

final class ClientDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $type,
        public readonly ResidencyEnum $residency,
        public readonly LanguageEnum $language,
        public readonly ?string $address,
        public readonly ?string $phone,
        public readonly ?string $email,
    ) {}

    public function isLegal(): bool
    {
        return $this->type === TypeEnum::LEGAL_ENTITY->value;
    }
}
