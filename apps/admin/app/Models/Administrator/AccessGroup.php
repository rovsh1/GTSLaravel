<?php

namespace App\Admin\Models\Administrator;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AccessGroup extends Model
{
    public $timestamps = false;

    protected $table = 'administrator_access_groups';

    protected $fillable = [
        'name',
        'role',
//        'members',
        'description'
    ];

//    public function rules() {
//        return $this->hasMany(Rule::class);
//    }
//
//    public function members(): \Ustabor\Custom\TabularSection
//    {
//        return $this->members ?? $this->members = new TabularSection($this, 'administrator_access_members', 'administrator_id');
//    }

//    public function getMembersAttribute() {
//        return $this->members()->values();
//    }
//
//    public function setMembersAttribute($members) {
//        $this->members()->values($members);
//    }

    public static function scopeWhereAdministrator($query, $user)
    {
        $query->whereExists(function ($query) use ($user) {
            $query->select(DB::raw(1))
                ->from('administrator_access_members as t')
                ->whereColumn('t.group_id', 'administrator_access_groups.id')
                ->where('t.administrator_id', $user->id);
        });
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
