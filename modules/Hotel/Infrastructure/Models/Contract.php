<?php

namespace Module\Hotel\Infrastructure\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sdk\Module\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'hotel_contracts';

    protected $fillable = [
        'hotel_id',
        'status',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }
}
