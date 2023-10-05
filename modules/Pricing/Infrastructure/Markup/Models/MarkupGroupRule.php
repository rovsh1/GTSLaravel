<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Markup\Models;

use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class MarkupGroupRule extends Model
{
    protected $table = 'client_markup_group_rules';

    protected $fillable = [
        'group_id',
        'hotel_id',
        'hotel_room_id',
        'value',
        'type'
    ];

    protected $casts = [
        'type' => MarkupValueTypeEnum::class
    ];
}
