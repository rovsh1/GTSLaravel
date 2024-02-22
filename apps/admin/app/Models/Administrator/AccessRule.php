<?php

namespace App\Admin\Models\Administrator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccessRule extends Model
{
    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'administrator_access_rules';

    protected $fillable = [
        'group_id',
        'resource',
        'permission',
        'flag'
    ];

    protected $casts = [
        'group_id' => 'int',
        'flag' => 'bool'
    ];

    public static function scopeWhereAdministrator($query, int $id)
    {
        $query
            ->join('administrator_access_groups', 'administrator_access_groups.id', '=', 'administrator_access_rules.group_id')
            ->whereExists(function ($query) use ($id) {
                $query->select(DB::raw(1))
                    ->from('administrator_access_members as t')
                    ->whereColumn('t.group_id', 'administrator_access_groups.id')
                    ->where('t.administrator_id', $id);
            });
    }
}
