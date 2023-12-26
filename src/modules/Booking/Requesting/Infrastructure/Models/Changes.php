<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Infrastructure\Models;

use DateTimeInterface;
use Module\Booking\Requesting\Domain\ValueObject\ChangeStatusEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $booking_id
 * @property ChangeStatusEnum $status
 * @property string $field
 * @property string $description
 * @property array|null $payload
 * @property DateTimeInterface $created_at
 */
class Changes extends Model
{
    public $timestamps = false;

    protected $primaryKey = false;

    public $incrementing = false;

    protected $table = 'booking_request_changes';

    protected $fillable = [
        'booking_id',
        'field',
        'status',
        'description',
        'payload',
        'created_at'
    ];

    protected $casts = [
        'booking_id' => 'int',
        'status' => ChangeStatusEnum::class,
        'payload' => 'array',
        'created_at' => 'datetime',
    ];
}
