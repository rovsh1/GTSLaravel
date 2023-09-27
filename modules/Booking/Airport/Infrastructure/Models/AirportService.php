<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Models;

use Module\Shared\Enum\Booking\TransferServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class AirportService extends Model
{
    protected $table = 'supplier_airport_services';

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
