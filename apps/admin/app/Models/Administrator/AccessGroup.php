<?php

namespace App\Admin\Models\Administrator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class AccessGroup extends Model
{
    use HasQuicksearch;

    private array $savingMembers;

    protected array $quicksearch = ['id', 'name%'];

    public $timestamps = false;

    protected $table = 'administrator_access_groups';

    protected $fillable = [
        'name',
        'role',
        'members',
        'description'
    ];

    public static function booted()
    {
        static::saved(function ($model) {
            if (isset($model->savingMembers)) {
                $model->members()->sync($model->savingMembers);
                unset($model->savingMembers);
            }
        });
    }

    public function getForeignKey()
    {
        return 'group_id';
    }

    public function rules(): HasMany
    {
        return $this->hasMany(AccessRule::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            Administrator::class,
            'administrator_access_members',
            'group_id',
            'administrator_id'
        );
    }

    public function getMembersAttribute()
    {
        return $this->members()->pluck('id')->toArray();
    }

    public function setMembersAttribute(array $members)
    {
        $this->savingMembers = $members;
    }

    public static function scopeWhereAdministrator($query, $user)
    {
        $query->whereExists(function ($query) use ($user) {
            $query->select(DB::raw(1))
                ->from('administrator_access_members as t')
                ->whereColumn('t.group_id', 'administrator_access_groups.id')
                ->where('t.administrator_id', $user->id);
        });
    }

    public static function scopeWithAdministratorsCount($query)
    {
        $query->addSelect(
            DB::raw(
                '(SELECT count(*) FROM administrator_access_members as t'
                . ' WHERE t.group_id=administrator_access_groups.id) as administrators_count'
            )
        );
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
