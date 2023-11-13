<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Adapter;

use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Dto\FileDto;

interface FileGeneratorAdapterInterface
{
    public function generate(string $filename, InvoiceId $invoiceId, OrderIdCollection $orderIds, ClientId $clientId, \DateTimeInterface $createdAt): FileDto;
}
