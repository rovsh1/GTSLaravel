<?php

namespace Module\Hotel\Infrastructure\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
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
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public function period(): Attribute
    {
        return Attribute::get(fn() => new CarbonPeriod($this->date_start, $this->date_end, 'P1D'));
    }

    public function scopeWhereRoomId(Builder $builder, int $roomId)
    {
        $builder->whereExists(function (QueryBuilder $query) use ($roomId) {
            $query
                ->join('hotel_contracts', 'hotel_contracts.id', '=', 'hotel_seasons.contract_id')
                ->select(DB::raw(1))
                ->from('hotel_rooms as t')
                ->whereColumn('t.hotel_id', 'hotel_contracts.hotel_id')
                ->where('t.id', $roomId);
        });
    }

    public function scopeWhereHotelId(Builder $builder, int $hotelId)
    {
        $builder->whereExists(function (QueryBuilder $query) use ($hotelId) {
            $query
                ->join('hotel_contracts', 'hotel_contracts.id', '=', 'hotel_seasons.contract_id')
                ->select(DB::raw(1))
                ->where('hotel_contracts.hotel_id', $hotelId)
                ->where('hotel_contracts.status', 1);
        });
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
}
