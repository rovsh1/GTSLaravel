<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Sdk\Module\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'name',

    ];

    public function requisites(): HasOne
    {
        return $this->hasOne(Requisites::class);
    }
}
