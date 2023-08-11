<?php

namespace Module\Booking\HotelBooking\Infrastructure\Models\Hotel;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Infrastructure\Models\Room;
use Module\Hotel\Infrastructure\Models\Room\QuotaStatusEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * Module\Hotel\Infrastructure\Models\Room\Quota
 *
 * @property int $id
 * @property int $room_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $release_days
 * @property QuotaStatusEnum $status
 * @property int $count_available
 * @property int $count_total
 * @method static \Illuminate\Database\Eloquent\Builder|RoomQuota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomQuota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomQuota query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomQuota whereCountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomQuota wherePeriod(CarbonPeriod $period)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomQuota whereBookingId(int $bookingId)
 * @mixin \Eloquent
 */
class RoomQuota extends Model
{
    protected $table = 'hotel_room_quota';

    public $timestamps = false;

    protected $fillable = [];

    protected $casts = [
        'date' => 'date',
        'status' => QuotaStatusEnum::class
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $query) {
            $query->selectSub(
                DB::table('booking_quota_reservation')
                    ->select('SUM(value)')
                    ->whereColumn('booking_quota_reservation.quota_id', '=', 'hotel_room_quota.id'),
                'count_unavailable'
            )
                ->select('(count_total - count_unavailable) as count_available');
        });
    }

    public function scopeWherePeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate());
    }

    public function scopeWhereBookingId(Builder $builder, int $bookingId): void
    {
        $builder->whereExists(function (\Illuminate\Database\Query\Builder $query) use ($bookingId) {
            $query->selectRaw('1')
                ->from('booking_hotel_rooms')
                ->whereColumn('booking_hotel_rooms.hotel_room_id', 'hotel_room_quota.room_id')
                ->where('booking_hotel_rooms.booking_id', $bookingId);
        });
    }
}
