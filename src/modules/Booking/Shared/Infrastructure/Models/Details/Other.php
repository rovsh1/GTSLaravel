<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models\Details;

use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\ServiceTypeEnum;

class Other extends Model
{
    protected $table = 'booking_other_details';

    protected $fillable = [
        'booking_id',
        'service_id',
        'date',
        'data',
    ];

    protected $casts = [
        'date' => 'datetime',
        'data' => 'array',
    ];

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::OTHER_SERVICE;
    }
}
