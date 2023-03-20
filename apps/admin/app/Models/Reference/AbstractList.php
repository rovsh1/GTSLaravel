<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AbstractList extends Model
{
    use HasTranslations;
    use HasQuicksearch;

    protected $quicksearch = ['%name%'];

    private static array $migrationAssoc = [
        'bed-type' => 3,
        'room-name' => 2,
        'room-type' => 1,
        'hotel-type' => 5,
        'usability-group' => 19,
        'hotel-service-type' => 100
    ];

    public $timestamps = false;

    protected array $translatable = ['name'];

    protected $table = 'r_enums';

    protected $fillable = [
        'name',
        'group_id'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->group_id = self::$migrationAssoc[$model->group];
        });
    }

    public static function booted()
    {
        static::addGlobalScope('group', function (Builder $builder) {
            $builder
                ->addSelect('r_enums.*')
                ->where('r_enums.group_id', self::$migrationAssoc[$builder->getModel()->group])
                ->joinTranslations()
                ->orderBy('name', 'asc');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
