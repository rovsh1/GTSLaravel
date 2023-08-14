<?php

namespace Module\Hotel\Infrastructure\Models\Room;

use Module\Shared\Enum\Booking\QuotaChangeTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class QuotaReservation extends Model
{
    protected $table = 'booking_quota_reservation';

    public $incrementing = false;

    protected $primaryKey = null;

    public const UPDATED_AT = null;

    protected $fillable = [
        'booking_id',
        'quota_id',
        'type',
        'value',
        'context',
    ];

    protected $casts = [
        'type' => QuotaChangeTypeEnum::class,
        'context' => 'array',
    ];
}
