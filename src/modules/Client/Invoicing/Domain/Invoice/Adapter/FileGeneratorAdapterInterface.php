<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Adapter;

use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Dto\FileDto;

interface FileGeneratorAdapterInterface
{
    public function generate(string $filename, OrderId $orderId, ClientId $clientId): FileDto;
}
