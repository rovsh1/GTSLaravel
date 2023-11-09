<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Client;

use Module\Client\Application\Admin\Dto\InvoiceDto;
use Module\Client\Application\Admin\Request\CreateInvoiceRequestDto;
use Module\Client\Application\Admin\UseCase\CreateInvoice;

class InvoiceAdapter
{
    public function create(int $clientId, array $orderIds): InvoiceDto
    {
        return app(CreateInvoice::class)->execute(
            new CreateInvoiceRequestDto(
                $clientId,
                $orderIds,
                null
            )
        );
    }
}
