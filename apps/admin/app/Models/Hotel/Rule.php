<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Admin\Models\Hotel\Rule
 *
 * @property int $id
 * @property int $hotel_id
 * @property string $name
 * @property string $text
 * @method static Builder|Rule newModelQuery()
 * @method static Builder|Rule newQuery()
 * @method static Builder|Rule query()
 * @method static Builder|Rule whereHotelId($value)
 * @method static Builder|Rule whereId($value)
 * @mixin \Eloquent
 */
class Rule extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $table = 'hotel_rules';

    protected $translatable = ['name', 'text'];

    protected $fillable = [
        'hotel_id',
        'name',
        'text',
    ];

    protected $casts = [
        'hotel_id' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_rules.*')
                ->joinTranslations();
        });
    }

    public function __toString()
    {
        return $this->name;
    }
}
