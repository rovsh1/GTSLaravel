<?php

namespace Module\Hotel\Infrastructure\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Sdk\Module\Database\Eloquent\Model;

class Season extends Model
{
    protected $table = 'hotel_seasons';

    protected $fillable = [
        'contract_id',
        'name',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'contract_id' => 'int',
        'hotel_id' => 'int',
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public function period(): Attribute
    {
        return Attribute::get(fn() => new CarbonPeriod($this->date_start, $this->date_end, 'P1D'));
    }

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_seasons.*')
                ->join('hotel_contracts', 'hotel_contracts.id', '=', 'hotel_seasons.contract_id')
                ->addSelect('hotel_contracts.hotel_id');
        });
    }

    public function scopeWhereRoomId(Builder $builder, int $roomId): void
    {
        $builder->whereExists(function (QueryBuilder $query) use ($roomId) {
            $query
                ->selectRaw(1)
                ->from('hotel_rooms as t')
                ->whereColumn('t.hotel_id', 'hotel_contracts.hotel_id')
                ->where('t.id', $roomId);
        });
    }

    public function scopeWhereHotelId(Builder $builder, int $hotelId): void
    {
        $builder
            ->where('hotel_contracts.hotel_id', $hotelId)
            ->where('hotel_contracts.status', 1);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
}
