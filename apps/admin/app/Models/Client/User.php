<?php

namespace App\Admin\Models\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Shared\Enum\Client\User\RoleEnum;
use Module\Shared\Enum\Client\User\StatusEnum;
use Module\Shared\Enum\GenderEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class User extends Model
{
    use HasQuicksearch, SoftDeletes;

    protected $table = 'users';

    protected array $quicksearch = [
        'id',
        'users.name%',
        'users.surname%',
        'users.patronymic%',
        'users.presentation%',
        'users.login%',
        'users.email%',
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
                ->leftJoin('clients', 'clients.id', '=', 'users.client_id')
                ->leftJoin('r_cities', 'r_cities.id', '=', 'clients.city_id')
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }

    public function scopeWhereCountryId(Builder $builder, int $countryId): void
    {
        $builder->where('users.country_id', $countryId);
    }

    public function __toString()
    {
        if ($this->surname || $this->name || $this->patronymic) {
            return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
        }

        return (string)$this->presentation;
    }
}