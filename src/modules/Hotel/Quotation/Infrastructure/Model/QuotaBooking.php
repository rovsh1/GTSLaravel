<?php

namespace Module\Hotel\Quotation\Infrastructure\Model;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Sdk\Booking\Enum\QuotaChangeTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class QuotaBooking extends Model
{
    protected $table = 'hotel_room_quota_booking';

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
        'booking_id' => 'int',
        'quota_id' => 'int',
        'value' => 'int',
    ];

    public static function batchInsert(
        QuotaChangeTypeEnum $type,
        int $bookingId,
        int $roomId,
        CarbonPeriod $period,
        int $count,
        array $context = []
    ): void {
        $type = $type->value;

        $insert = [];
        $bindings = [];
        foreach ($period as $date) {
            $insert[] = '(?,(SELECT id FROM hotel_room_quota WHERE `room_id`=? AND `date`=?),?,?,?)';
            $bindings[] = $bookingId;
            $bindings[] = $roomId;
            $bindings[] = $date->format('Y-m-d');
            $bindings[] = $type;
            $bindings[] = $count;
            $bindings[] = '[]';
        }

        DB::insert(
            'INSERT INTO `hotel_room_quota_booking`'
            . ' (`booking_id`,`quota_id`,`type`,`value`,`context`)'
            . ' VALUES ' . implode(',', $insert),
            $bindings
        );
    }
}
