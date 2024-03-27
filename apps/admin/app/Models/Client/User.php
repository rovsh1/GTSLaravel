<?php

namespace App\Admin\Models\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class User extends Model
{
    use HasQuicksearch;

    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'client_id',
        'country_id',
        'presentation',
        'gender',
        'login',
        'password',
        'email',
        'phone',
        'post_id',
        'address',
        'note',
        'status',
        'role',
        'birthday',
        'image',
        'recovery_hash',
    ];

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

    public function isActive(): bool
    {
        return (bool)$this->status;
    }

    public function getDisplayName(): string
    {
        return (string)$this->presentation;
    }

    public function __toString()
    {
        return $this->getDisplayName();
    }
}
