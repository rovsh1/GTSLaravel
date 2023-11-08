<?php

namespace Module\Hotel\Pricing\Infrastructure\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\Model;

class Season extends Model
{
    protected $table = 'hotel_seasons';

    protected $casts = [
        'contract_id' => 'int',
        'hotel_id' => 'int',
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_seasons.*')
                ->join('hotel_contracts', 'hotel_contracts.id', '=', 'hotel_seasons.contract_id')
                ->addSelect('hotel_contracts.hotel_id');
        });
    }

    public function scopeWhereHotelId(Builder $builder, int $hotelId): void
    {
        $builder
            ->where('hotel_contracts.hotel_id', $hotelId)
            ->where('hotel_contracts.status', 1);
    }

    public function scopeWhereDateIncluded(Builder $builder, DateTimeInterface $date): void
    {
        $builder
            ->whereDate('hotel_seasons.date_start', '<=', $date)
            ->whereDate('hotel_seasons.date_end', '>=', $date);
    }
}
