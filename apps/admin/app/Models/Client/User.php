<?php

namespace App\Admin\Models\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Client\User\RoleEnum;
use Sdk\Shared\Enum\Client\User\StatusEnum;
use Sdk\Shared\Enum\GenderEnum;

class User extends Model
{
    use HasQuicksearch, SoftDeletes;

    protected $table = 'users';

    protected array $quicksearch = [
        'users.id',
        'users.%name%',
        'users.%surname%',
        'users.%patronymic%',
        'users.%presentation%',
        'users.%login%',
        'users.%email%',
    ];

    protected $fillable = [
        'client_id',
        'country_id',
        'name',
        'surname',
        'patronymic',
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

    protected $casts = [
        'role' => RoleEnum::class,
        'status' => StatusEnum::class,
        'gender' => GenderEnum::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('users.*')
                ->join('r_countries', 'r_countries.id', '=', 'users.country_id')
                ->joinTranslatable('r_countries', 'name as country_name')
                ->leftJoin('clients', 'clients.id', '=', 'users.client_id')
                ->addSelect('clients.name as client_name')
                ->leftJoin('r_cities', 'r_cities.id', '=', 'clients.city_id')
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }

    public function scopeWhereCountryId(Builder $builder, int $countryId): void
    {
        $builder->where('users.country_id', $countryId);
    }

    public function getDisplayName(): string
    {
        if ($this->surname || $this->name || $this->patronymic) {
            return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
        }

        return (string)$this->presentation;
    }

    public function __toString()
    {
        return $this->getDisplayName();
    }
}
