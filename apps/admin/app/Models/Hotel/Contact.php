<?php

namespace App\Admin\Models\Hotel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Shared\Enum\ContactTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

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
}
