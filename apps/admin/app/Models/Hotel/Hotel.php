<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasQuicksearch;

    //use SoftDeletes;

    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected array $translatable = ['name'];

    protected $table = 'hotels';

    protected $fillable = [
        'city_id',
        'type_id',
        'name',
        'rating',
        'address',
//->addAttribute('citycenter_distance','number', ['default' => 0, 'nonnegative' => true, 'allowZero' => true]),
        'geolocation',
        'latitude',
        'longitude',
        'zipcode',
//    'text',
        'status',
//    'visible_for',
    ];

    protected $casts = [
        'city_id' => 'int',
        'type_id' => 'int',
        'rating' => 'int',
        'status' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotels.*')
                ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
                ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
                ->join('r_enums', 'r_enums.id', '=', 'hotels.type_id')
                ->joinTranslatable('r_cities', 'name as city_name')
                ->joinTranslatable('r_countries', 'name as country_name')
                ->joinTranslatable('r_enums', 'name as type_name');
        });
    }

    public function scopeWhereId($query, $id)
    {
        $query->where('hotels.id', $id);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
