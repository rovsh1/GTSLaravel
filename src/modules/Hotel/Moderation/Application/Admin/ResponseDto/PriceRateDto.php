<?php

namespace Module\Hotel\Moderation\Application\Admin\ResponseDto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class PriceRateDto extends Dto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $description
    ) {}
}
