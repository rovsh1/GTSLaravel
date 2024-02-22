<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Adapter;

use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Shared\Dto\FileDto;

interface FileGeneratorAdapterInterface
{
    public function generate(string $filename, OrderId $orderId): FileDto;
}
