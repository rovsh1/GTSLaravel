<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Sdk\Module\Database\Eloquent\Model;
use Supplier\Traveline\Infrastructure\Models\TravelineReservationStatusEnum;

/**
 * Supplier\Traveline\Infrastructure\Models\TravelineReservation
 *
 * @property int $id
 * @property int $reservation_id
 * @property TravelineReservationStatusEnum $status
 * @property array $data
 * @property CarbonInterface|null $accepted_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
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