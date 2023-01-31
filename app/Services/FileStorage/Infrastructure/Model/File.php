<?php

namespace GTS\Services\FileStorage\Infrastructure\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\DB;

class File extends BaseModel
{
    protected $table = 'files';

    public $timestamps = true;

    protected $primaryKey = 'guid';

    public $incrementing = false;

    protected $fillable = [
        'type',
        'parent_id',
        'name',
        'index'
    ];

    public static function createFromParent(string $fileType, int $parentId, ?string $name = null)
    {
        return static::create([
            'guid' => static::generateGuid(),
            'type' => static::hashType($fileType),
            'parent_id' => $parentId,
            'name' => $name
        ]);
    }

    public static function findByGuid($guid): ?static
    {
        return static::where('guid', $guid)->first();
    }

    public static function scopeWhereGuid($query, string $guid)
    {
        $query->where('guid', $guid);
    }

    public static function scopeWhereType($query, string $fileType)
    {
        $query->where('type', static::hashType($fileType));
    }

    public static function scopeWhereParent($query, $parent)
    {
        $query->where('parent_id', is_object($parent) ? $parent->id : $parent);
    }

    public static function scopeParentColumn(Builder $builder, string $fileType, string $columnName)
    {
        $entity = $builder->getModel();
        $builder->addSelect(
            DB::raw(
                '(SELECT guid FROM files'
                . ' WHERE parent_id=`' . $entity->getTable() . '`.id'
                . ' AND type="' . static::hashType($fileType) . '"'
                . ' LIMIT 1) as `' . $columnName . '`'
            )
        );
    }

    private static function hashType(string $type): string
    {
        return md5($type);
    }

    private static function generateGuid(): string
    {
        do {
            $guid = md5(uniqid());
        } while (static::whereGuid($guid)->exists());

        return $guid;
    }
}
