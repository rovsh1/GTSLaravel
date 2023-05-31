<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Models;

class TranslationItem extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'name';

    protected $table = 'translation_items';

    protected $fillable = [
        'name',
        'value_ru',
        'value_en',
        'value_uz',
    ];
}
