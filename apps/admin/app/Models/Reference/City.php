<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class City extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];
    protected array $translatable = ['name', 'text'];

    protected $table = 'r_cities';

    protected $fillable = [
        'name',
        'country_id',
        'text'
    ];

    public static function booted()
    {
        static::addGlobalTranslationScope();

        static::addGlobalScope('country', function (Builder $builder) {
            $builder
                ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
                ->joinTranslatable('r_countries', 'name as country_name');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
