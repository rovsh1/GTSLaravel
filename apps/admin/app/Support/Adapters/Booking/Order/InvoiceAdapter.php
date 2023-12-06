<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Order;

use Module\Client\Invoicing\Application\Dto\InvoiceDto;
use Module\Client\Invoicing\Application\UseCase\CancelOrderInvoice;
use Module\Client\Invoicing\Application\UseCase\CreateInvoice;
use Module\Client\Invoicing\Application\UseCase\GetDocumentFileInfo;
use Module\Client\Invoicing\Application\UseCase\GetOrderInvoice;
use Module\Client\Invoicing\Application\UseCase\SendInvoiceToClient;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;

class InvoiceAdapter
{
    public function create(int $orderId): InvoiceDto
    {
        return app(CreateInvoice::class)->execute($orderId);
    }

    public function cancel(int $orderId): void
    {
        app(CancelOrderInvoice::class)->execute($orderId);
    }

    public function send(int $orderId): void
    {
        app(SendInvoiceToClient::class)->execute($orderId);
    }

    public function get(int $orderId): ?InvoiceDto
    {
        return app(GetOrderInvoice::class)->execute($orderId);
    }

    public function getFile(int $orderId): ?FileInfoDto
    {
        return app(GetDocumentFileInfo::class)->execute($orderId);
    }
}
