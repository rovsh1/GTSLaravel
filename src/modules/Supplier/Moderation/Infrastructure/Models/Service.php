<?php

namespace Module\Supplier\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\ServiceTypeEnum;

class Service extends Model
{
    use HasTranslations;

    protected $table = 'supplier_services';

    protected $translatable = ['title'];

    protected $fillable = [
        'supplier_id',
        'title',
        'type',
        'data',
    ];

    protected $casts = [
        'type' => ServiceTypeEnum::class,
        'data' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('supplier_services.*')
                ->joinTranslations();
        });
    }
}
