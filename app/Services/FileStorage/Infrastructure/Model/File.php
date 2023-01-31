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

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'type',
        'entity_id',
        'name',
        'index'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->guid = static::generateGuid();
        });
    }

    public static function createFromParent(string $fileType, int $entityId, ?string $name = null)
    {
        return static::create([
            'type' => static::hashType($fileType),
            'entity_id' => $entityId,
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

    public static function scopeWhereEntity($query, $entity)
    {
        $query->where('entity_id', is_object($entity) ? $entity->id : $entity);
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
