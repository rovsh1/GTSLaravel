<?php

namespace App\Admin\Models\Client;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class User extends \App\Shared\Models\User
{
    use HasQuicksearch;

    protected array $quicksearch = [
        'users.id',
        'users.%presentation%',
        'users.%login%',
        'users.%email%',
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('users.*')
                ->leftJoin('r_countries', 'r_countries.id', '=', 'users.country_id')
                ->joinTranslatable('r_countries', 'name as country_name')
                ->leftJoin('clients', 'clients.id', '=', 'users.client_id')
                ->addSelect('clients.name as client_name');
        });
    }

    public function scopeWhereCountryId(Builder $builder, int $countryId): void
    {
        $builder->where('users.country_id', $countryId);
    }
}
