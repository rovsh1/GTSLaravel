<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Client;

use Illuminate\Support\Facades\Facade;
use Module\Client\Application\Admin\Dto\InvoiceDto;

/**
 * @method static InvoiceDto create(int $clientId, int[] $orderIds)
 */
class InvoiceAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Client\InvoiceAdapter::class;
    }
}
