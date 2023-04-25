<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;

class Currency extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected array $translatable = ['name'];

    protected $table = 'r_currencies';

    protected $fillable = [
        'name',
        'code_num',
        'code_char',
        'sign'
    ];

    public static function booted()
    {
        static::addGlobalTranslationScope();
    }

    public function __toString()
    {
        return (string)($this->name ?: $this->code_char);
    }
}
