<?php

namespace Module\Client\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\ContactTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'client_contacts';

    protected $fillable = [
        'client_id',
        'type',
        'value',
        'description',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'type' => ContactTypeEnum::class,
    ];

    public function scopeWhereIsAddress(Builder $builder): void
    {
        $builder->where('type', ContactTypeEnum::ADDRESS);
    }

    public function scopeWhereIsEmail(Builder $builder): void
    {
        $builder->where('type', ContactTypeEnum::EMAIL);
    }

    public function scopeWhereIsPhone(Builder $builder): void
    {
        $builder->where('type', ContactTypeEnum::PHONE);
    }
}
