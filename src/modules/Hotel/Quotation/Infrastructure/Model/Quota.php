<?php

namespace Module\Hotel\Quotation\Infrastructure\Model;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Module\Hotel\Moderation\Infrastructure\Models\Room;
use Sdk\Booking\Enum\QuotaChangeTypeEnum;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Hotel\QuotaStatusEnum;

/**
 * Module\Hotel\Infrastructure\Models\Room\Quota
 *
 * @property int $id
 * @property int $room_id
 * @property Carbon $date
 * @property int $release_days
 * @property QuotaStatusEnum $status
 * @property int $count_total
 * @property int $count_booked
 * @property int $count_reserved
 * @property int $count_available
 * @property-read Room|null $room
 * @method static Builder|Quota newModelQuery()
 * @method static Builder|Quota newQuery()
 * @method static Builder|Quota query()
 * @method static Builder|Quota whereCountTotal($value)
 * @method static Builder|Quota whereCountReserved($value)
 * @method static Builder|Quota whereCountAvailable($value)
 * @method static Builder|Quota whereCountBooked($value)
 * @method static Builder|Quota whereDate($value)
 * @method static Builder|Quota whereRoomId($value)
 * @method static Builder|Quota whereHotelId(int $hotelId, int $roomId = null)
 * @method static Builder|Quota whereSold()
 * @method static Builder|Quota whereOpened()
 * @method static Builder|Quota whereClosed()
 * @method static Builder|Quota whereHasAvailable(int $count = 0)
 * @method static Builder|Quota whereReleaseDaysBelowOrEqual(int $releaseDays)
 * @method static Builder|Quota wherePeriod(CarbonPeriod $period)
 * @method static Builder|Quota withCountColumns()
 * @mixin \Illuminate\Database\Eloquent\Model
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
    ];

    protected $appends = [
        'count_reserved',
        'count_booked',
        'count_available',
    ];

    protected $attributes = [
        'status' => QuotaStatusEnum::OPEN,
    ];

    protected $casts = [
        'date' => 'date',
        'status' => QuotaStatusEnum::class,
        'room_id' => 'int',
        'release_days' => 'int',
        'count_total' => 'int',
        'count_reserved' => 'int',
        'count_booked' => 'int',
        'count_available' => 'int',
    ];

    public static function batchUpdateStatus(int $roomId, CarbonPeriod $period, QuotaStatusEnum $status): void
    {
        $updateData = array_map(fn($date) => [
            'room_id' => $roomId,
            'date' => $date,
            'status' => $status
        ], $period->toArray());

        Quota::upsert($updateData, ['date', 'room_id'], ['status']);
    }

    public function scopeWhereDate(Builder $builder, \DateTimeInterface $date): void
    {
        $builder->whereRaw('DATE(date) = ?', [$date->format('Y-m-d')]);
    }

    public function scopeWherePeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->where('date', '>=', $period->getStartDate())
            ->where('date', '<=', $period->getEndDate());
    }

    public function scopeWhereHotelId(Builder $builder, int $hotelId, int $roomId = null): void
    {
        if ($roomId) {
            $builder->where('room_id', $roomId);
        } else {
            $builder->whereHas('room', function (Builder $query) use ($hotelId) {
                $query->whereHotelId($hotelId);
            });
        }
    }

    public function scopeWhereRoomId(Builder $builder, int $roomId = null): void
    {
        $builder->where('room_id', $roomId);
    }

    public function scopeWhereSold(Builder $builder): void
    {
        $builder
            ->where('count_total', '>', 0)
            ->whereRaw('count_total <= (' . self::buildCountQuery() . ')');
    }

    public function scopeWhereOpened(Builder $builder): void
    {
        $builder->whereStatus(QuotaStatusEnum::OPEN);
    }

    public function scopeWhereClosed(Builder $builder): void
    {
        $builder->whereStatus(QuotaStatusEnum::CLOSE);
    }

    public function scopeWhereHasAvailable(Builder $builder, int $count = 0): void
    {
        $builder->whereRaw('(count_total - (' . self::buildCountQuery() . ') >= ?)', [$count]);
    }

    public function scopeWhereReleaseDaysBelowOrEqual(Builder $builder, int $releaseDays): void
    {
        $builder->where('release_days', '<=', $releaseDays);
    }

    public function scopeWithCountColumns(Builder $builder): void
    {
        $builder->addSelect('hotel_room_quota.*');
        self::selectCountColumn($builder, QuotaChangeTypeEnum::RESERVED);
        self::selectCountColumn($builder, QuotaChangeTypeEnum::BOOKED);
        $builder->selectRaw('(hotel_room_quota.count_total - (' . self::buildCountQuery() . ')) as count_available');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    private static function selectCountColumn(Builder $builder, QuotaChangeTypeEnum $type): void
    {
        $builder->selectRaw(
            '(' . self::buildCountQuery(true) . ') as count_' . strtolower($type->name),
            [$type->value]
        );
    }

    private static function buildCountQuery(bool $typeWhere = false): string
    {
        return 'SELECT COALESCE(SUM(value), 0)'
            . ' FROM hotel_room_quota_booking'
            . ' WHERE quota_id=hotel_room_quota.id'
            . ($typeWhere ? ' AND type=?' : '');
    }

//    public function countAvailable(): Attribute
//    {
//        return Attribute::get(fn(int|null $val, array $attributes) => (int)($attributes['count_available'] ?? 0));
//    }
//
//    public function countBooked(): Attribute
//    {
//        return Attribute::get(fn(int|null $val, array $attributes) => (int)($attributes['count_booked'] ?? 0));
//    }
//
//    public function countReserved(): Attribute
//    {
//        return Attribute::get(fn(int|null $val, array $attributes) => (int)($attributes['count_reserved'] ?? 0));
//    }
}
