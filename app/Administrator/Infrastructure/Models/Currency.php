<?php

namespace GTS\Administrator\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;

use GTS\Shared\Infrastructure\Models\Model;

class Currency extends Model
{
    use HasTranslations, HasQuicksearch;

    protected array $quicksearch = ['id', 'name%'];
    protected array $translatable = ['name'];

    protected $table = 'r_currencies';

    protected $fillable = [
        'code_num',
        'code_char',
        'sign',
        'rate'
    ];
}
