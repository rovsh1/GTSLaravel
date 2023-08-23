<?php

namespace Module\Support\MailManager\Domain\Service\DataBuilder\DataDto;

use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;

final class SenderDataDto implements DataDtoInterface
{
    public function __construct(
        public readonly string $presentation,
        public readonly ?string $postName,
        public readonly string $email,
        public readonly string $phone,
    ) {
    }
}