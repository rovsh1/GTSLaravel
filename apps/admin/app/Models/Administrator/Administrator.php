<?php

namespace App\Admin\Models\Administrator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

/**
 * @property int id
 * @property int post_id
 * @property string presentation
 * @property string name
 * @property string surname
 * @property string login
 * @property string email
 * @property string phone
 * @property bool superuser
 * @property bool status
 * @property string remember_token
 */
class Administrator extends Authenticatable
{
    use HasFactory;
    use HasQuicksearch;
    use Notifiable;

    protected $subscriptions;

    protected $quicksearch = ['id', 'presentation%', 'login%', 'email%'];

    protected $table = 'administrators';

    protected $fillable = [
        'post_id',
        'presentation',
        'name',
        'surname',
        'login',
        'password',
        'phone',
        'email',
        'gender',
        'status',
        'superuser',
        'avatar_guid',

        'groups',
    ];

    protected $hidden = [
        'password',
        //'remember_token',
    ];

    protected $casts = [
        'post_id' => 'int',
        'superuser' => 'bool',
        'status' => 'bool'
    ];

//    protected static function booted()
//    {
//        parent::saving(function (Administrator $model) {
//            if ($model->isDirty('status') && $model->status == 0) {
//                $model->remember_token = null;
//            }
//        });
//    }

    private array $savingGroups;

    public static function booted()
    {
        static::saved(function ($model) {
            if (isset($model->savingGroups)) {
                $model->groups()->sync($model->savingGroups);
                unset($model->savingGroups);
            }
        });
    }

    public function isSuperuser(): bool
    {
        return (bool)$this->superuser;
    }

    public function isActive(): bool
    {
        return (bool)$this->status;
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(AccessGroup::class, 'administrator_access_members', 'administrator_id', 'group_id');
    }

    public function getGroupsAttribute(): array
    {
        return $this->groups()->pluck('id')->toArray();
    }

    public function setGroupsAttribute(array $groups)
    {
        $this->savingGroups = $groups;
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

    public static function scopeJoinPost($query)
    {
        $query
            ->leftJoin('r_enums', 'r_enums.id', '=', 'administrators.post_id')
            ->joinTranslatable('r_enums', 'name as post_name');
    }

    public static function scopeWhereActive($query)
    {
        $query->where('administrators.status', 1);
    }

    public static function scopeWhereLogin($query, $login)
    {
        $query->where('login', $login);
    }

}
