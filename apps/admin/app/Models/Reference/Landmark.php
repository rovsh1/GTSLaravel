<?php

namespace App\Admin\Models\Reference;

use App\Admin\Models\HasCoordinates;
use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Landmark extends Model
{
    use HasQuicksearch;
    use HasTranslations;
    use HasCoordinates;

    protected array $quicksearch = ['id', 'name%'];

    protected array $translatable = ['name'];

    public $timestamps = false;

    protected $table = 'r_landmarks';

    protected $fillable = [
        'city_id',
        'type_id',
        'name',
        'in_city',
        'address',
        'address_lat',
        'address_lon',
        'city_distance',
        //'system_status',
    ];

    protected $casts = [
        'city_id' => 'int',
        'type_id' => 'int',
        'in_city' => 'bool',
        'address_lat' => 'float',
        'address_lon' => 'float',
        'city_distance' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_landmarks.*')
                ->addSelect('r_landmark_types.name as type_name')
                ->join('r_cities', 'r_cities.id', '=', 'r_landmarks.city_id')
                ->leftJoin('r_landmark_types', 'r_landmark_types.id', '=', 'r_landmarks.type_id')
                ->joinTranslations()
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    protected function getLatitudeField(): string
    {
        return 'address_lat';
    }

    protected function getLongitudeField(): string
    {
        return 'address_lon';
    }
}
