<?php

namespace GTS\Hotel\Infrastructure\Models\Room;

use GTS\Shared\Custom\Database\Eloquent\HasTranslations;
use GTS\Shared\Infrastructure\Models\Model;
use Illuminate\Database\Eloquent\Builder;

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
        static::addGlobalScope('roomName', function (Builder $builder) {
            $builder->where('group_id', 2);
        });
    }
}
