<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\Dto;

use Module\Shared\Enum\Client\ResidencyEnum;
use Module\Shared\Enum\Client\TypeEnum;

final class ClientDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $type,
        public readonly ResidencyEnum $residency,
        public readonly ?string $address,
        public readonly ?string $phone,
    ) {}

    public function isLegal(): bool
    {
        return $this->type === TypeEnum::LEGAL_ENTITY->value;
    }
}
