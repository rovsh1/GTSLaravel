<?php

declare(strict_types=1);

namespace App\Admin\Http\Requests\Client;

final class PhysicalDto
{
    public function __construct(
        public readonly int $countryId,
        public readonly int $gender
    ) {}
}
