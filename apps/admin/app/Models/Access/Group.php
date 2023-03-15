<?php

namespace App\Admin\Models\Access;

use App\Admin\Models\Administrator\AccessRule;
use Custom\Framework\Database\Eloquent\Model;
use Custom\Framework\Database\Eloquent\TabularSection;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    public $timestamps = false;

    protected $table = 'administrator_access_groups';

    protected $fillable = [
        'name',
        'role',
        'members',
        'description'
    ];

    public function rules()
    {
        return $this->hasMany(AccessRule::class);
    }

    public function members(): TabularSection
    {
        return new TabularSection($this, 'administrator_access_members', 'administrator_id');
    }

    public function getMembersAttribute()
    {
        return $this->members->values();
    }

    public function setMembersAttribute($members)
    {
        dd($members);
        $this->members->values($members);
    }

    public static function scopeWhereUser($query, $user)
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
