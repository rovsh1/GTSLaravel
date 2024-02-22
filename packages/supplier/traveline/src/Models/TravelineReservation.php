<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Module\Database\Eloquent\Model;

/**
 * Supplier\Traveline\Infrastructure\Models\TravelineReservation
 *
 * @property int $id
 * @property int $reservation_id
 * @property int $hotel_id
 * @property CarbonInterface|null $accepted_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineReservation whereReservationId($value)
 * @method static Builder|TravelineReservation whereHotelId(int $hotelId)
 * @mixin \Eloquent
 */
class TravelineReservation extends Model
{
    protected $table = 'traveline_reservations';

    protected $fillable = [
        'reservation_id',
        'hotel_id',
        'accepted_at'
    ];

    protected $casts = [
        'accepted_at' => 'datetime'
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->whereExists(function (Query $query) {
                $query->selectRaw(1)
                    ->from('traveline_hotels')
                    ->whereColumn('traveline_hotels.hotel_id', 'traveline_reservations.hotel_id')
                    ->whereColumn('traveline_reservations.created_at', '>=', 'traveline_hotels.created_at');
            });
        });
    }
}
