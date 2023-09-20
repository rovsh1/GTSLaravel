<?php

namespace Module\Support\FileStorage\Infrastructure\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class File extends BaseModel
{
    protected $table = 'files';

    public $timestamps = true;

    protected $primaryKey = 'guid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->guid = static::generateGuid();
        });
    }

    public static function findByGuid($guid): ?static
    {
        return static::where('guid', $guid)->first();
    }

    private static function generateGuid(): string
    {
        do {
            $guid = md5(uniqid());
        } while (static::whereGuid($guid)->exists());

        return $guid;
    }

    public function scopeWhereGuid($query, string $guid): void
    {
        $query->where('guid', $guid);
    }
}
