<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Order;

use Illuminate\Support\Facades\Facade;
use Module\Client\Invoicing\Application\Dto\InvoiceDto;
use Sdk\Shared\Dto\FileInfoDto;

/**
 * @method static InvoiceDto create(int $orderId)
 * @method static InvoiceDto|null get(int $orderId)
 * @method static FileInfoDto|null getFile(int $orderId)
 * @method static void cancel(int $orderId)
 * @method static void send(int $orderId)
 */
class InvoiceAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Order\InvoiceAdapter::class;
    }
}
