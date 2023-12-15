<?php

namespace Module\Client\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Client\DocumentTypeEnum;
use Sdk\Shared\Enum\Contract\StatusEnum;

class Document extends Model
{
    protected $table = 'client_documents';

    protected $fillable = [
        'client_id',
        'type',
        'number',
        'status',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'type' => DocumentTypeEnum::class,
        'status' => StatusEnum::class,
        'date_start' => 'datetime',
        'date_end' => 'datetime',
    ];

    public function scopeOnlyContracts(Builder $builder): void
    {
        $builder->where('type', DocumentTypeEnum::CONTRACT);
    }

    public function scopeWhereActive(Builder $builder): void
    {
        $builder->where('status', StatusEnum::ACTIVE);
    }
}
