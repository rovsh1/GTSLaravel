<?php

namespace Module\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Constant extends Model
{
    public $timestamps = false;

    protected $table = 's_constants';

    protected $fillable = [
        'key',
        'value',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'bool'
    ];

    public function __toString(): string
    {
        return (string)$this->key;
    }

    public static function scopeWhereEnabled($query)
    {
        $query->where('s_constants.enabled', 1);
    }
}
