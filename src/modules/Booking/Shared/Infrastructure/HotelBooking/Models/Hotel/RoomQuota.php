<?php

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Models\Hotel;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Moderation\Infrastructure\Models\Room\QuotaStatusEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * Module\Hotel\Infrastructure\Models\Room\Quota
 *
 * @property int $id
 * @property int $room_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $release_days
 * @property QuotaStatusEnum $status
 * @property int $count_total
 * @property int $count_unavailable
 * @property int $count_available
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
            $query->addSelect('hotel_room_quota.*');
            $query->selectSub(
                DB::table('booking_quota_reservation')
                    ->selectRaw('SUM(value)')
                    ->whereColumn('booking_quota_reservation.quota_id', '=', 'hotel_room_quota.id'),
                'count_unavailable'
            );
        });
    }

    public function scopeWherePeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereDate('date', '>=', $period->getStartDate())
            ->whereDate('date', '<=', $period->getEndDate());
    }

    public function countAvailable(): Attribute
    {
        return Attribute::get(fn() => $this->count_total - ($this->count_unavailable ?? 0));
    }
}
