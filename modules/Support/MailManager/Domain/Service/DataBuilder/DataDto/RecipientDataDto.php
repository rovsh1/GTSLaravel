<?php

namespace Module\Support\MailManager\Domain\Service\DataBuilder\DataDto;

use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;

final class RecipientDataDto implements DataDtoInterface
{
    public function __construct(
        public readonly string $presentation
    ) {
    }
}