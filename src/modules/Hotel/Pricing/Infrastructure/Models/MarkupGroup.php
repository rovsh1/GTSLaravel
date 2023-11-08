<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Infrastructure\Models;

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

    public static function findByClientId(int $clientId)
    {
        return static::query()
            ->addSelect('client_markup_groups.*')
            ->join('clients', 'clients.markup_group_id', '=', 'client_markup_groups.id')
            ->where('clients.id', $clientId)
            ->first();
    }
}
