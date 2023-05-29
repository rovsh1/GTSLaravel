<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Attributes\MapInputName;
use Sdk\Module\Foundation\Support\Dto\Dto;

class OrderDto extends Dto
{
    public function __construct(
        public readonly int $id,
        #[MapInputName('client_id')]
        public readonly int $clientId,
    ) {}
}
