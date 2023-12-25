<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Infrastructure\Models;

use DateTimeInterface;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $booking_id
 * @property string $field
 * @property mixed $before
 * @property mixed $after
 * @property DateTimeInterface $created_at
 */
class Changes extends Model
{
    public $timestamps = false;

    protected $table = 'booking_request_changes';

    protected $fillable = [
        'booking_id',
        'field',
        'before',
        'after',
        'created_at'
    ];

    protected $casts = [
        'booking_id' => 'int',
        'created_at' => 'datetime',
    ];
}
