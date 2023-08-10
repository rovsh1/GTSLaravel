<?php

namespace Module\Hotel\Infrastructure\Models\Room;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Domain\ValueObject\QuotaChangeTypeEnum;
use Module\Hotel\Infrastructure\Models\Room;
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
 * @property int $count_booked
 * @property int $count_reserved
 * @property-read Room|null $room
 * @method static \Illuminate\Database\Eloquent\Builder|Quota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountReserved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereCountBooked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereHotelId(int $hotelId)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereSold()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereStopped()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota whereAvailable()
 * @method static \Illuminate\Database\Eloquent\Builder|Quota wherePeriod(CarbonPeriod $period)
 * @method static \Illuminate\Database\Eloquent\Builder|Quota addCountColumns()
 * @mixin \Eloquent
 */
class Quota extends Model
{
    protected $table = 'hotel_room_quota';

    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'date',
        'release_days',
        'status',
        'count_total',//Общее кол-во квот которое вводит менеджер
        'count_available',//остаток после = count_total - count_booked - count_reserved
        'count_booked',
        'count_reserved',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => QuotaStatusEnum::class
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeWherePeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate());
    }

    public function scopeWhereHotelId(Builder $builder, int $hotelId): void
    {
        $builder->whereHas('room', function (Builder $query) use ($hotelId) {
            $query->whereHotelId($hotelId);
        });
    }

    public function scopeWhereSold(Builder $builder): void
    {
        $builder->where('count_available', 0)
            ->where('count_total', '>', 0);
    }

    public function scopeWhereStopped(Builder $builder): void
    {
        $builder->whereStatus(QuotaStatusEnum::CLOSE);
    }

    public function scopeWhereAvailable(Builder $builder): void
    {
        $builder->whereStatus(QuotaStatusEnum::OPEN)
            ->where('count_available', '>', 0);
    }

    public function scopeAddCountColumns(Builder $builder): void
    {
        $this->addCountColumnsQuery(
            $builder,
            [QuotaChangeTypeEnum::RESERVE_BY_BOOKING, QuotaChangeTypeEnum::CANCEL_RESERVE_BY_BOOKING],
            'count_reserve'
        );
        $this->addCountColumnsQuery(
            $builder,
            [QuotaChangeTypeEnum::BOOK_BY_BOOKING, QuotaChangeTypeEnum::CANCEL_BOOK_BY_BOOKING],
            'count_booked'
        );
        $builder->selectRaw('(count_total - count_booked - count_reserve) as count_available');
    }

    private function addCountColumnsQuery(Builder $builder, array $events, string $alias): Builder
    {
        return $builder->selectSub(
            DB::table('hotel_room_quota_history')
                ->select('SUM(value)')
                ->whereColumn('hotel_room_quota_history.quota_id', '=', 'hotel_room_quota.id')
                ->whereIn('event', $events),
            $alias
        );
    }
}
