<?php

namespace App\Hotel\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class User extends Authenticatable
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'presentation%'];

    protected $table = 'hotel_users';

    protected $fillable = [
        'hotel_id',
        'presentation',
        'name',
        'surname',
        'patronymic',
        'login',
        'password',
        'email',
        'phone',
        'status',
    ];

    protected $casts = [
        'hotel_id' => 'int',
        'status' => 'int',
    ];

    public static function booted()
    {
//        static::addGlobalScope('default', function (Builder $builder) {
//            $builder
//                ->addSelect('hotel_users.*')
//                ->addSelect('hotels.name as hotel_name')
//                ->join('hotels', 'hotels.id', '=', 'hotel_users.hotel_id')
//                ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
//                ->joinTranslatable('r_cities', 'name as city_name');
//        });
    }

    public static function findByLogin(string $login): ?User
    {
        return static::query()
            ->whereLogin($login)
            ->first();
    }

    public function isActive(): bool
    {
        return (bool)$this->status;
    }

    public function setAttribute($key, $value)
    {
        if ($key === 'password') {
            $value = $value ? Hash::make($value) : null;
        }

        return parent::setAttribute($key, $value);
    }

    public function __toString()
    {
        return (string)$this->presentation;
    }
}