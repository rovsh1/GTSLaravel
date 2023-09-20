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
//            $builder
//                ->addSelect('clients.*')
//                ->join('r_cities', 'r_cities.id', '=', 'clients.city_id')
//                ->joinTranslatable('r_cities', 'name as city_name')
//                ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
//                ->joinTranslatable('r_countries', 'name as country_name')
//                ->leftJoin('r_currencies', 'r_currencies.id', '=', 'clients.currency_id')
//                ->joinTranslatable('r_currencies', 'name as currency_name');
        });
    }

    public function __toString()
    {
        if ($this->surname || $this->name || $this->patronymic) {
            return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
        }

        return (string)$this->presentation;
    }
}
