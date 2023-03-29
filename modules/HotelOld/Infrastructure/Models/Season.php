<?php

namespace Module\HotelOld\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

/**
 * Module\HotelOld\Infrastructure\Models\Season
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $contract_id
 * @property string $name
 * @property \Custom\Framework\Support\DateTime $date_from
 * @property \Custom\Framework\Support\DateTime $date_to
 * @property-read \Module\HotelOld\Infrastructure\Models\Contract|null $contract
 * @property-read \Module\HotelOld\Infrastructure\Models\Hotel|null $hotel
 * @method static \Illuminate\Database\Eloquent\Builder|Season newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season query()
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereRoomId(int $roomId)
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

    public function scopeWhereRoomId(Builder $builder, int $roomId)
    {
        $builder->addSelect("{$this->getTable()}.*");

        $joinableTable = with(new Room)->getTable();
        $builder->leftJoin(
            $joinableTable,
            function (JoinClause $join) use ($joinableTable) {
                $join->on("{$joinableTable}.hotel_id", '=', "{$this->getTable()}.hotel_id");
            }
        )->addSelect("{$joinableTable}.id as room_id");

        $builder->where("{$joinableTable}.id", $roomId);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
}
