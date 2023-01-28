<?php

namespace GTS\Administrator\Infrastructure\Models\Access;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rule extends Model
{

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'administrator_access_rules';

    protected $fillable = [
        'group_id',
        'resource',
        'permission',
        //'flag'
    ];

}
