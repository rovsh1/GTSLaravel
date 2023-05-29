<?php

namespace App\Admin\Models\System;

use Illuminate\Database\Eloquent\Model;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Constant extends Model
{
    use HasQuicksearch;

    public $timestamps = false;

    protected $quicksearch = ['key%', '%value%'];

    protected $table = 's_constants';

    protected $fillable = [
        'key',
        'value',
        'name',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'bool'
    ];

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public static function scopeWhereEnabled($query)
    {
        $query->where('s_constants.enabled', 1);
    }
}
