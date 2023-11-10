<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Client;

use Module\Client\Invoicing\Application\Dto\InvoiceDto;
use Module\Client\Invoicing\Application\Request\CreateInvoiceRequestDto;
use Module\Client\Invoicing\Application\UseCase\CreateInvoice;

class InvoiceAdapter
{
    public function create(int $clientId, array $orderIds): InvoiceDto
    {
        return app(CreateInvoice::class)->execute(
            new CreateInvoiceRequestDto(
                $clientId,
                $orderIds,
            )
        );
    }
}
