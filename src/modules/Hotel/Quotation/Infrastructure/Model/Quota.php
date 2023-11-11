<?php

namespace Module\Hotel\Quotation\Infrastructure\Model;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Moderation\Infrastructure\Models\Room;
use Module\Shared\Enum\Booking\QuotaChangeTypeEnum;
use Module\Shared\Enum\Hotel\QuotaStatusEnum;
use Sdk\Module\Database\Eloquent\Model;

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
 * @method static Builder|Quota whereClosed()
 * @method static Builder|Quota whereHasAvailable(int $count = 0)
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

    public function scopeWithCountColumns(Builder $builder): void
    {
        $builder->addSelect('hotel_room_quota.*');
        self::selectCountColumn($builder, QuotaChangeTypeEnum::RESERVED);
        self::selectCountColumn($builder, QuotaChangeTypeEnum::BOOKED);
        $builder->selectRaw('(hotel_room_quota.count_total - (' . self::buildCountQuery() . ')) as count_available');
    }

    private static function selectCountColumn(Builder $builder, QuotaChangeTypeEnum $type): void
    {
        $builder->selectRaw(
            '(' . self::buildCountQuery(true) . ') as count_' . strtolower($type->name),
            [QuotaChangeTypeEnum::RESERVED]
        );
    }

    private static function buildCountQuery(bool $typeWhere = false): string
    {
        return 'SELECT COALESCE(SUM(value), 0)'
            . ' FROM hotel_room_quota_booking'
            . ' WHERE quota_id=hotel_room_quota.id'
            . ($typeWhere ? ' AND type=?' : '');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
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

//    public function recalculateCountColumns(): void
//    {
//        $this->update([
//            'count_available' => $this->count_total
//                - $this->calculateCount([QuotaChangeTypeEnum::RESERVED, QuotaChangeTypeEnum::BOOKED]),
//            'count_reserved' => $this->calculateCount([QuotaChangeTypeEnum::RESERVED]),
//            'count_booked' => $this->calculateCount([QuotaChangeTypeEnum::BOOKED]),
//        ]);
//    }
//
//    private function calculateCount(array $types): int
//    {
//        return DB::table('booking_quota_reservation')
//            ->selectRaw('COALESCE(SUM(value), 0) as c')
//            ->whereColumn('hotel_room_quota.id', '=', 'booking_quota_reservation.quota_id')
//            ->whereIn('type', $types)
//            ->value('c');
//    }
}
