<?php

namespace App\Admin\Models\Hotel\Reference;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected array $translatable = ['name'];

    protected $table = 'r_hotel_services';

    protected $fillable = [
        'type_id',
        'name'
    ];

    protected $casts = [
        'type_id' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_hotel_services.*')
                ->leftJoin('r_enums', 'r_enums.id', '=', 'r_hotel_services.type_id')
                ->joinTranslations($builder->getModel()->translatable)
                ->joinTranslatable('r_enums', 'name as type_name');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
