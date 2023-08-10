<?php

namespace Module\Hotel\Infrastructure\Models\Room;

use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Hotel\Domain\ValueObject\QuotaChangeTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class QuotaValue extends Model
{
    use SoftDeletes;

    protected $table = 'hotel_room_quota_values';

    protected $primaryKey = null;

    public const UPDATED_AT = null;

    protected $fillable = [
        'quota_id',
        'type',
        'value',
        'context',
    ];

    protected $casts = [
        'type' => QuotaChangeTypeEnum::class,
    ];
}
