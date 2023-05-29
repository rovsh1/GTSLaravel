<?php

namespace Module\Hotel\Infrastructure\Models\Room;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Hotel\Application\Enums\QuotaAvailabilityEnum;
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
}
