<?php

namespace App\Admin\Models\Client;

use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Models\HasPeriod;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

/**
 * App\Admin\Models\Client\CurrencyRate
 *
 * @property int $id
 * @property int $client_id
 * @property int $currency_id
 * @property \Sdk\Module\Support\DateTime $date_start
 * @property \Sdk\Module\Support\DateTime $date_end
 * @property float $rate
 * @property CarbonPeriod $period
 * @property-read string $client_name
 * @property-read string $currency_name
 * @property-read string $currency_code_char
 * @property \Sdk\Module\Support\DateTime|null $created_at
 * @property \Sdk\Module\Support\DateTime|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Hotel> $hotels
 * @method static Builder|CurrencyRate newModelQuery()
 * @method static Builder|CurrencyRate newQuery()
 * @method static Builder|CurrencyRate query()
 * @method static Builder|CurrencyRate quicksearch($term)
 * @method static Builder|CurrencyRate whereClientId($value)
 * @method static Builder|CurrencyRate whereCreatedAt($value)
 * @method static Builder|CurrencyRate whereCurrencyId($value)
 * @method static Builder|CurrencyRate whereDateEnd($value)
 * @method static Builder|CurrencyRate whereDateStart($value)
 * @method static Builder|CurrencyRate whereId($value)
 * @method static Builder|CurrencyRate whereRate($value)
 * @method static Builder|CurrencyRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CurrencyRate extends Model
{
    use HasQuicksearch, HasPeriod;

    protected array $quicksearch = ['clients.name%'];

    protected $table = 'client_currency_rates';

    protected $fillable = [
        'client_id',
        'currency_id',
        'date_start',
        'date_end',
        'rate',
        'hotel_ids',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    private array $savingHotelIds;

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('client_currency_rates.*')
                ->join('clients', 'clients.id', '=', 'client_currency_rates.client_id')
                ->join('r_currencies', 'r_currencies.id', '=', 'client_currency_rates.currency_id')
                ->addSelect('clients.name as client_name')
                ->addSelect('r_currencies.code_char as currency_code_char')
                ->joinTranslatable('r_currencies', 'name as currency_name');
        });

        static::saved(function (self $model): void {
            if (isset($model->savingHotelIds)) {
                $model->hotels()->sync($model->savingHotelIds);
                unset($model->savingHotelIds);
            }
        });
    }

    public function scopeWhereStartPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereBetween('date_start', [$period->getStartDate(), $period->getEndDate()]);
    }

    public function scopeWhereEndPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereBetween('date_end', [$period->getStartDate(), $period->getEndDate()]);
    }

    public function scopeWhereCurrencyId(Builder $builder, int $id): void
    {
        $builder->where('client_currency_rates.currency_id', $id);
    }

    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(
            Hotel::class,
            'client_currency_rate_hotels',
            'rate_id',
            'hotel_id',
        );
    }

    public function hotelIds(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->hotels()->pluck('id')->toArray(),
            set: function (array $hotelIds) {
                $this->savingHotelIds = $hotelIds;

                return [];
            }
        );
    }

    public function __toString()
    {
        return $this->client_name;
    }
}