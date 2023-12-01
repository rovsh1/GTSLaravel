<?php

declare(strict_types=1);

namespace Module\Integration\Traveline\Infrastructure\Models\Legacy;

use Custom\Framework\Database\Eloquent\Model;

class ReservationStatusLog extends Model
{
    protected $table = 'reservation_status_log';

    const CREATED_AT = 'created';
    const UPDATED_AT = null;

    protected $fillable = [
        'reservation_id',
        'user_id',
        'source',
        'status',
        'notification',
        'created',
        'description',
    ];

    protected $casts = [
        'status' => ReservationStatusEnum::class,
    ];

    public static function log(
        int $reservationId,
        ReservationStatusEnum $status,
        int $source,
        ?int $userId = null
    ): void {
        ReservationStatusLog::create([
            'reservation_id' => $reservationId,
            'user_id' => $userId,
            'source' => $source,
            'status' => $status,
            'notification' => 0,
            'description' => null,
        ]);
    }
}
