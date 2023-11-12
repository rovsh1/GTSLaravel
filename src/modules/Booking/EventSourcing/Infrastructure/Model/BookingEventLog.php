<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Infrastructure\Model;

use Sdk\Module\Database\Eloquent\Model;

class BookingEventLog extends Model
{
    public $timestamps = false;

    protected $table = 'booking_history';

    protected $fillable = [
        'booking_id',
        'event',
        'payload',
        'context',
        'created_at'
    ];

    protected $casts = [
        'booking_id' => 'int',
        'payload' => 'array',
        'context' => 'array',
        'created_at' => 'datetime',
    ];
}
