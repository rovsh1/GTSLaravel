<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Hotel\QuotaStatusEnum;

class HotelRoomQuota extends Model
{
    protected $table = 'traveline_hotel_room_quota';

    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'date',
        'release_days',
        'status',
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
        'count_available' => 'int',
    ];

    public function cancelQuotaReserve(int $count): void
    {
        $this->count_available += $count;
        $this->save();
    }

    public function reserveQuota(int $count): void
    {
        if ($this->count_available < $count) {
            throw new \RuntimeException('Quota limit exceed');
        }
        $this->count_available -= $count;
        $this->save();
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
            $builder->whereExists(function (Query $query) use ($hotelId) {
                $query->selectRaw(1)
                    ->from('hotel_rooms')
                    ->whereColumn('hotel_rooms.id', 'traveline_hotel_room_quota.room_id')
                    ->where('hotel_id', $hotelId);
            });
        }
    }

    public function scopeWhereRoomId(Builder $builder, int $roomId = null): void
    {
        $builder->where('room_id', $roomId);
    }

    public function scopeWhereSold(Builder $builder): void
    {
        $builder->where('count_available', '=', 0);
    }

    public function scopeWhereOpened(Builder $builder): void
    {
        $builder->whereStatus(QuotaStatusEnum::OPEN);
    }

    public function scopeWhereClosed(Builder $builder): void
    {
        $builder->whereStatus(QuotaStatusEnum::CLOSE);
    }

    public function scopeWhereHasAvailable(Builder $builder, int $count = 1): void
    {
        $builder->where('count_available', '>=', $count);
    }

    public function scopeWhereReleaseDaysBelowOrEqual(Builder $builder, int $releaseDays): void
    {
        $builder->where('release_days', '<=', $releaseDays);
    }

}
