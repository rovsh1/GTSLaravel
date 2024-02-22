<?php

namespace App\Admin\Models\Client;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\Model;

class Legal extends Model
{
    protected $table = 'client_legals';

    protected $fillable = [
        'name',
        'client_id',
        'city_id',
        'industry_id',
        'address',
        'requisites',
    ];

    protected $casts = [
        'requisites' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('client_legals.*')
                ->leftJoin('r_enums', 'r_enums.id', '=', 'client_legals.industry_id')
                ->joinTranslatable('r_enums', 'name as industry_name');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
