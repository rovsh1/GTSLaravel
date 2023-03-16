<?php

namespace App\Admin\Models\Administrator;

use App\Admin\Support\View\Sidebar\Menu\Group;
use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Administrator extends Authenticatable
{
    use HasFactory;
    use HasQuicksearch;
    use Notifiable;

    protected $subscriptions;

    protected $quicksearch = ['id', 'presentation%', 'login%', 'email%'];

    protected $table = 'administrators';

    protected $fillable = [
        'presentation',
        'name',
        'surname',
        'patronymic',
        'login',
        'password',
        'phone',
        'email',
        'gender',
        'status',
        'image',
        'superuser',

        'groups',
    ];

    protected $hidden = [
        'password',
        //'remember_token',
    ];

    public $timestamps = true;

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::addGlobalScope('administrator', function (Builder $builder) {
//            $builder->addSelect('administrators.*');
//            UserAvatar::scopeEntityColumn($builder, 'avatar');
//        });
//    }

    public function accessGroups()
    {
        return \App\Admin\Models\Access\Group::whereUser($this);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'administrator_access_members', 'administrator_id', 'group_id');
    }

    public function getGroupsAttribute()
    {
        return $this->groups()->pluck('id')->toArray();
    }

    public function setGroupsAttribute(array $groups)
    {
        $this->groups()->sync($groups);
    }

//    public function hasRole($role): bool
//    {
//        return $this->accessGroups()
//            ->where('role', AccessRole::fromString($role))
//            ->exists();
//    }

    public static function findByLogin($login)
    {
        return static::query()
            ->whereLogin($login)
            ->first();
    }

    public static function loginExists($login): bool
    {
        return (bool)static::where('login', $login)->first();
    }

    public function setAttribute($key, $value)
    {
        if ($key === 'password') {
            $value = Hash::make($value);
        }

        return parent::setAttribute($key, $value);
    }

//    public function avatar()
//    {
//        return UserAvatar::findByEntity($this);
//    }

    public function __toString(): string
    {
        return (string)$this->presentation;
    }

//    public static function scopeWhereAccessRole($query, $role)
//    {
//        $query->whereExists(function ($query) use ($role) {
//            $query->select(DB::raw(1))
//                ->from('administrator_access_groups as g')
//                ->join('administrator_access_members as m', 'm.group_id', '=', 'g.id')
//                ->whereColumn('m.administrator_id', 'administrators.id')
//                ->where('g.role', $role);
//        });
//    }

    public static function scopeWhereLogin($query, $login)
    {
        $query->where('login', $login);
    }

}
