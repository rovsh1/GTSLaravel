<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Infrastructure\Models;

use Module\Shared\Enum\Booking\TransferServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class TransferService extends Model
{
    protected $table = 'supplier_transfer_services';

    protected $fillable = [
        'supplier_id',
        'name',
        'type',
    ];

    protected $casts = [
        'supplier_id' => 'int',
        'type' => TransferServiceTypeEnum::class,
    ];
}
