<?php

namespace App\Hotel\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

/**
 * @property int $hotel_id
 * @property string|null $presentation
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string $email
 * @property string|null $phone
 */
class Administrator extends Authenticatable
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'presentation%'];

    protected $table = 'hotel_administrators';

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
//                ->addSelect('hotel_administrators.*')
//                ->addSelect('hotels.name as hotel_name')
//                ->join('hotels', 'hotels.id', '=', 'hotel_administrators.hotel_id')
//                ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
//                ->joinTranslatable('r_cities', 'name as city_name');
//        });
    }

    public static function findByLogin(string $login): ?Administrator
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
