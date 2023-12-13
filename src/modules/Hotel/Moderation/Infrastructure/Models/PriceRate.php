<?php

namespace Module\Hotel\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Hotel\MealPlanTypeEnum;

/**
 * @property int $id
 * @property int $hotel_id
 * @property int|null $meal_plan_id
 * @property string|null $meal_plan_name
 * @property string $name
 * @property string $description
 * @property int[] $room_ids
 * @method static \Illuminate\Database\Eloquent\Builder|PriceRate whereHotelId(int $value)
 */
class PriceRate extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $table = 'hotel_price_rates';

    protected $translatable = ['name', 'description'];

    protected $casts = [
        'meal_plan_type' => MealPlanTypeEnum::class,
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_price_rates.*')
                ->joinTranslations()
                ->leftJoin('r_hotel_meal_plans', 'r_hotel_meal_plans.id', 'hotel_price_rates.meal_plan_id')
                ->addSelect('r_hotel_meal_plans.type as meal_plan_type')
                ->joinTranslatable('r_hotel_meal_plans', 'name as meal_plan_name');
        });
    }
}
