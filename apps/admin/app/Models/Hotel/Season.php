<?php

namespace App\Admin\Models\Hotel;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property int $contract_id
 * @property CarbonPeriod $period
 * @property CarbonInterface $date_start
 * @property CarbonInterface $date_end
 * @property-read Contract $contract
 * @method static Builder|Season newModelQuery()
 * @method static Builder|Season newQuery()
 * @method static Builder|Season query()
 * @method static Builder|Season whereHotelId(int $id)
 * @method static Builder|Season withContractNumber()
 */
class Season extends Model
{
    public $timestamps = false;

    protected $table = 'hotel_seasons';

    protected $fillable = [
        'contract_id',
        'name',
        'date_start',
        'date_end',
        'period',
    ];

    protected $casts = [
        'hotel_id' => 'int',
    ];

    public function scopeWithContractNumber(Builder $builder): void
    {
        $builder
            ->addSelect('hotel_seasons.*')
            ->join('hotel_contracts', 'hotel_contracts.id', '=', 'hotel_seasons.contract_id')
            ->addSelect('hotel_contracts.status as contract_status')
            ->addSelect('hotel_contracts.id as contract_number');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function period(): Attribute
    {
        return Attribute::make(
            get: fn() => new CarbonPeriod($this->date_start, $this->date_end),
            set: fn(CarbonPeriod $period) => [
                'date_start' => $period->getStartDate(),
                'date_end' => $period->getEndDate()
            ]
        );
    }

    public function scopeWhereHotelId(Builder $builder, int $hotelId): void
    {
        $contractIdsQuery = \DB::table('hotel_contracts')->select('id')->where('hotel_id', $hotelId);
        $builder->whereIn('hotel_seasons.contract_id', $contractIdsQuery);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
