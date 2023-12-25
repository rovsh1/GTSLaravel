<?php

namespace App\Hotel\Models;

use App\Admin\Models\Client\Contact;
use App\Admin\Models\Client\Legal;
use App\Admin\Models\Client\User;
use App\Admin\Models\Order\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Client extends \Module\Client\Moderation\Infrastructure\Models\Client
{
    use HasQuicksearch, SoftDeletes;

    protected array $quicksearch = ['id', 'clients.%name%'];

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
        $builder->whereExists(function (Query $query) use ($types) {
            $legalsTable = with(new Legal)->getTable();
            $query->selectRaw(1)
                ->from($legalsTable)
                ->whereColumn("{$legalsTable}.client_id", 'clients.id')
                ->whereIn('type', $types);
        });
    }

    public function scopeWhereHasActiveOrders(Builder $builder): void
    {
        $builder->whereHas('orders', function (Builder $query) {
            $query->whereIn('status', Order::getActiveStatuses());
        });
    }

    public function legals(): HasMany
    {
        return $this->hasMany(Legal::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}