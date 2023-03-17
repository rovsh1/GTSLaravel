<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Landmark extends Model
{
    public $timestamps = false;

    protected $table = 'ref_landmarks';

    protected $fillable = [
        'city_id',
        'type_id',
        'name',
        'address',
//        'address_lat',
//        'address_lon',
        //'center_distance',
        //'system_status',
    ];

    protected $casts = [
        'city_id' => 'int',
        'type_id' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('ref_landmarks.*')
                ->addSelect('ref_landmark_types.name as type_name')
                ->join('r_cities', 'r_cities.id', '=', 'ref_landmarks.city_id')
                ->leftJoin('ref_landmark_types', 'ref_landmark_types.id', '=', 'ref_landmarks.type_id')
                //->joinTranslations($builder->getModel()->translatable)
                ->joinTranslatable('r_cities', 'name as city_name');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
