<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Carbon\CarbonImmutable;
use Sdk\Shared\Dto\FileDto;

class VoucherDto
{
    public function __construct(
        public readonly CarbonImmutable $createdAt,
        public readonly FileDto $file,
        public readonly ?FileDto $wordFile,
        public readonly ?CarbonImmutable $sendAt,
    ) {}
}
