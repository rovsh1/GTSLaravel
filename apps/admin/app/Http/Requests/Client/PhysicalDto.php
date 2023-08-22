<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Client;

use Sdk\Module\Foundation\Support\Dto\Dto;

class PhysicalDto extends Dto
{
    public function __construct(
        public readonly int $gender
    ) {
    }
}
