<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Country extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];
    protected array $translatable = ['name'];

    protected $table = 'r_countries';

    protected $fillable = [
        'code',
        'name',
        'default',
        'phone_code',
        'language',
        'currency_id'
    ];

    protected $casts = [
        'default' => 'bool'
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_countries.*')
                ->joinTranslations()
                //TODO add priority column
                //->orderBy('priority', 'desc')
                ->orderBy('name', 'asc');
        });
    }

    public function scopeWhereHasCity($query)
    {
        $query->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('r_cities as t')
                ->whereColumn('t.country_id', 'r_countries.id');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
