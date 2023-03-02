<?php

namespace Module\Integration\Traveline\Infrastructure\Models\Legacy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Module\Shared\Infrastructure\Models\Model;

/**
 * Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservation
 *
 * @property int $id
 * @property int $reservation_id
 * @property \Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservationStatusEnum $status
 * @property array $data
 * @property \Custom\Framework\Support\DateTime|null $accepted_at
 * @property \Custom\Framework\Support\DateTime|null $created_at
 * @property \Custom\Framework\Support\DateTime|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereUpdatedAt($value)
 * @method static Builder|TravelineReservation whereHotelId(int $hotelId)
 * @mixin \Eloquent
 */
class TravelineReservation extends Model
{
    protected $table = 'traveline_reservations';

    protected $fillable = [
        'reservation_id',
        'status',
        'data',
        'accepted_at'
    ];

    protected $casts = [
        'status' => TravelineReservationStatusEnum::class,
        'data' => 'array',
        'accepted_at' => 'datetime'
    ];

    public function scopeWhereHotelId(Builder $builder, int $hotelId)
    {
        $builder->addSelect("{$this->getTable()}.*");

        $joinableTable = 'reservation';
        $builder->leftJoin(
            $joinableTable,
            function (JoinClause $join) use ($joinableTable) {
                $join->on("{$joinableTable}.id", '=', "{$this->getTable()}.reservation_id");
            }
        )->addSelect("{$joinableTable}.hotel_id as hotel_id");

        $builder->where("{$joinableTable}.hotel_id", $hotelId);
    }
}
