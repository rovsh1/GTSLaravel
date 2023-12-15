<?php

declare(strict_types=1);

namespace Sdk\Shared\Dto\Mail;

final class AttachmentDto
{
    public function __construct(
        public readonly string $guid
    ) {}
}
