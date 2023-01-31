<?php

namespace GTS\Hotel\Infrastructure\Models\Room;

use GTS\Shared\Infrastructure\Models\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * GTS\Hotel\Infrastructure\Models\Room\NameTranslation
 *
 * @property int $translatable_id
 * @property string $name
 * @property string $language
 * @property string $description
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class NameTranslation extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'r_enums_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'language',
        'description'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('language', function (Builder $builder) {
            //@todo прокинуть язык из конфига или откуда-то?
            $builder->where('language', 'ru');
        });
    }
}
