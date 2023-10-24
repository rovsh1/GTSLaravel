<?php

declare(strict_types=1);

namespace App\Admin\Models\Pricing;

use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class MarkupGroupRule extends Model
{
    protected $table = 'client_markup_group_rules';

    protected $fillable = [
        'group_id',
        'hotel_id',
        'room_id',
        'value',
        'type'
    ];

    protected $casts = [
        'type' => MarkupValueTypeEnum::class
    ];

    public function scopeWithDetails(Builder $builder): void
    {
        $builder
            ->addSelect('client_markup_group_rules.*')
            ->join('hotels', 'hotels.id', '=', 'client_markup_group_rules.hotel_id')
            ->addSelect('hotels.name as hotel_name')
            ->leftJoin('hotel_rooms', 'hotel_rooms.id', '=', 'client_markup_group_rules.room_id')
            ->joinTranslatable('hotel_rooms', 'name as hotel_room_name');
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('client_markup_group_rules.id', $id);
    }
}
