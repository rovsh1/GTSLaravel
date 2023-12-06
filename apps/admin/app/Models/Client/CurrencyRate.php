<?php

namespace App\Admin\Models\Client;

use App\Admin\Support\Models\HasPeriod;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;

/**
 * App\Admin\Models\Client\CurrencyRate
 *
 * @property int $id
 * @property int $client_id
 * @property CurrencyEnum $currency
 * @property \Sdk\Module\Support\DateTime $date_start
 * @property \Sdk\Module\Support\DateTime $date_end
 * @property float $rate
 * @property CarbonPeriod $period
 * @property-read string $client_name
 * @property-read string $currency_name
 * @property-read string $currency_code_char
 * @property \Sdk\Module\Support\DateTime|null $created_at
 * @property \Sdk\Module\Support\DateTime|null $updated_at
 * @method static Builder|CurrencyRate newModelQuery()
 * @method static Builder|CurrencyRate newQuery()
 * @method static Builder|CurrencyRate query()
 * @method static Builder|CurrencyRate quicksearch($term)
 * @method static Builder|CurrencyRate whereClientId($value)
 * @method static Builder|CurrencyRate whereCreatedAt($value)
 * @method static Builder|CurrencyRate whereCurrency($value)
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
        'currency',
        'date_start',
        'date_end',
        'rate',
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('client_currency_rates.*')
                ->join('clients', 'clients.id', '=', 'client_currency_rates.client_id')
                ->join('r_currencies', 'r_currencies.code_char', '=', 'client_currency_rates.currency')
                ->addSelect('clients.name as client_name')
                ->addSelect('r_currencies.code_char as currency_code_char')
                ->joinTranslatable('r_currencies', 'name as currency_name');
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

    public function scopeWhereCurrency(Builder $builder, int $id): void
    {
        $builder->where('client_currency_rates.currency', $id);
    }

    public function __toString()
    {
        return $this->client_name;
    }
}
