<?php

namespace App\Hotel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\ContactTypeEnum;

class Contact extends Model
{
    protected $table = 'hotel_contacts';

    protected $fillable = [
        'hotel_id',
        'employee_id',
        'type',
        'value',
        'description',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'type' => ContactTypeEnum::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

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
