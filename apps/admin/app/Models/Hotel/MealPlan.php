<?php

namespace App\Admin\Models\Hotel;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Hotel\MealPlanTypeEnum;

/**
 * @property int id
 * @property string $name
 * @property MealPlanTypeEnum $type
 **/
class MealPlan extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $table = 'r_hotel_meal_plans';

    protected $translatable = ['name'];

    protected $fillable = [
        'name',
        'type',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_hotel_meal_plans.*')
                ->joinTranslations();
        });
    }

    public function __toString()
    {
        return $this->name;
    }
}
