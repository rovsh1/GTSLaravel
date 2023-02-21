<?php

namespace Module\Hotel\Infrastructure\Models\Room;

use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Module\Hotel\Infrastructure\Models\Room\Name
 *
 * @property int $id
 * @property int $group_id
 * @property int|null $display_in_report
 * @property int|null $reservation_status
 * @property int $deletion_mark
 * @property-read string $name
 * @property-write array $translatable
 * @method static Builder|Name joinTranslations($columns = null, $locale = null)
 * @method static Builder|Name newModelQuery()
 * @method static Builder|Name newQuery()
 * @method static Builder|Name query()
 * @method static Builder|Name whereDeletionMark($value)
 * @method static Builder|Name whereDisplayInReport($value)
 * @method static Builder|Name whereGroupId($value)
 * @method static Builder|Name whereId($value)
 * @method static Builder|Name whereReservationStatus($value)
 * @mixin \Eloquent
 */
class Name extends Model
{
    use HasTranslations;

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'r_enums';

    protected array $translatable = ['name'];

    protected $fillable = [
        'group_id',
        'display_in_report',
        'reservation_status',
        'deletion_mark',
    ];

    public static function booted()
    {
        static::addGlobalTranslationScope();
        static::addGlobalScope('roomName', function (Builder $builder) {
            //@todo 2 - это айди енума, нужно как то их получать
            $builder->where('group_id', 2);
        });
    }
}
