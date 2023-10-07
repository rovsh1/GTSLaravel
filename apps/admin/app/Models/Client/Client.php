<?php

namespace App\Admin\Models\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Shared\Enum\Client\ResidencyEnum;
use Module\Shared\Enum\Client\StatusEnum;
use Module\Shared\Enum\Client\TypeEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Client extends Model
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'clients.name%'];

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'city_id',
        'currency',
        'is_b2b',
        'type',
        'status',
        'residency',
        'description',
        'markup_group_id'
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'status' => StatusEnum::class,
        'type' => TypeEnum::class,
        'residency' => ResidencyEnum::class,
        'is_b2b' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('clients.*')
                ->join('r_cities', 'r_cities.id', '=', 'clients.city_id')
                ->joinTranslatable('r_cities', 'name as city_name')
                ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
                ->joinTranslatable('r_countries', 'name as country_name')
                ->leftJoin('r_currencies', 'r_currencies.code_char', '=', 'clients.currency')
                ->joinTranslatable('r_currencies', 'name as currency_name')
                ->leftJoin('administrator_clients', 'administrator_clients.client_id', 'clients.id')
                ->addSelect('administrator_clients.administrator_id')
                ->join('client_markup_groups', 'client_markup_groups.id', '=', 'clients.markup_group_id')
                ->addSelect('client_markup_groups.name as markup_group_name');
        });
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('clients.id', $id);
    }

    public function scopeWhereLegalEntityType(Builder $builder, array $types): void
    {
        $builder->whereExists(function (\Illuminate\Database\Query\Builder $query) use ($types) {
            $legalsTable = with(new Legal)->getTable();
            $query->selectRaw(1)
                ->from($legalsTable)
                ->whereColumn("{$legalsTable}.client_id", 'clients.id')
                ->whereIn('type', $types);
        });
    }

    public function legals(): HasMany
    {
        return $this->hasMany(Legal::class);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
