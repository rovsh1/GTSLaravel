<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Models;

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

    public static function findByRoomId(int $clientId, int $roomId)
    {
        return static::query()
            ->join('client_markup_groups', 'client_markup_group_rules.id', '=', 'client_markup_group_rules.group_id')
            ->join('clients', 'clients.markup_group_id', '=', 'client_markup_groups.id')
            ->where('clients.id', $clientId)
            ->whereRaw(
                'client_markup_group_rules.room_id=?'
                . ' OR (client_markup_group_rules.room_id IS NULL'
                . ' AND EXISTS(SELECT 1 FROM hotel_rooms as t'
                . ' WHERE t.hotel_id=client_markup_group_rules.hotel_id AND t.id=?)'
                . ')',
                [$roomId, $roomId]
            )
            ->orderBy('client_markup_group_rules.room_id', 'DESC')
            ->first();
    }
}
