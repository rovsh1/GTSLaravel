<?php

namespace GTS\Hotel\Infrastructure\Models\Room;

use GTS\Shared\Custom\Database\Eloquent\HasTranslations;
use Illuminate\Database\Eloquent\Builder;

use GTS\Shared\Infrastructure\Models\Model;

/**
 * GTS\Hotel\Infrastructure\Models\Room\Name
 *
 * @property int $group_id
 * @property bool $display_in_report
 * @property int $reservation_status
 * @property bool $deletion_mark
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Name extends Model
{
    use HasTranslations;

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'r_enums';

    protected $fillable = [
        'group_id',
        'display_in_report',
        'reservation_status',
        'deletion_mark'
    ];

    protected $casts = [
        'display_in_report' => 'boolean',
        'deletion_mark' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('group_id', function (Builder $builder) {
            //@todo laravel игнорирует данный скоуп, если эта таблица используется как Through для relations
            $builder->where('group_id', 2);
        });
    }
}
