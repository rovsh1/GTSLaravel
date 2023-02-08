<?php

namespace GTS\Administrator\Application\Dto\Administrator;

use Custom\Framework\Foundation\Support\Dto\Dto;

class AdministratorDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly string $presentation
    ) {}
}
