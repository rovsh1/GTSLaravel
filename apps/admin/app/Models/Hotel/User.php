<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class User extends Model
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
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_users.*')
                ->addSelect('hotels.name as hotel_name')
                ->join('hotels', 'hotels.id', '=', 'hotel_users.hotel_id')
                ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
                ->joinTranslatable('r_cities', 'name as city_name');
        });
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
