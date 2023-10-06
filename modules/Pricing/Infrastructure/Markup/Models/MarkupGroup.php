<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Markup\Models;

use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class MarkupGroup extends Model
{
    protected $table = 'client_markup_groups';

    protected $fillable = [
        'id',
        'name',
        'value',
        'type'
    ];

    protected $casts = [
        'type' => MarkupValueTypeEnum::class
    ];
}
