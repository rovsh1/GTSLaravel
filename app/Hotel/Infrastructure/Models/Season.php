<?php

namespace GTS\Hotel\Infrastructure\Models;

use GTS\Shared\Infrastructure\Models\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * GTS\Hotel\Infrastructure\Models\Season
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $contract_id
 * @property string $name
 * @property \Custom\Framework\Support\DateTime $date_from
 * @property \Custom\Framework\Support\DateTime $date_to
 * @method static \Illuminate\Database\Eloquent\Builder|Season newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season query()
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereName($value)
 * @method static Builder|Season withContract()
 * @method static Builder|Season withHotel()
 * @mixin \Eloquent
 */
class Season extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'hotel_seasons';

    protected $fillable = [
        'hotel_id',
        'contract_id',
        'name',
        'date_from',
        'date_to',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function scopeWithHotel(Builder $builder) {

    }

    public function scopeWithContract(Builder $builder) {

    }
}
