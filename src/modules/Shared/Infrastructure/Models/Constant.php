<?php

namespace Module\Shared\Infrastructure\Models;

class Constant extends Model
{
    public $timestamps = false;

    protected $table = 's_constants';

    protected $fillable = [
        'key',
        'value'
    ];

    public function __toString(): string
    {
        return (string)$this->key;
    }
}
