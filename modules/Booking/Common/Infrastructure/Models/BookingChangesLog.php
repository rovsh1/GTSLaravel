<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class BookingChangesLog extends Model
{
    public $timestamps = false;

    protected $table = 'booking_changes_log';

    protected $fillable = [
        'event',
        'event_type',
        'order_id',
        'booking_id',
        'payload',
        'context',
        'created_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'context' => 'array',
        'created_at' => 'datetime',
        'event_type' => EventTypeEnum::class,
    ];
}
