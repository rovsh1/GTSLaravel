<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Usability extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected array $translatable = ['name'];

    protected $table = 'r_usabilities';

    protected $fillable = [
        'group_id',
        'name',
        'popular',
    ];

    protected $casts = [
        'group_id' => 'int',
        'popular' => 'bool',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_usabilities.*')
                ->join('r_enums', 'r_enums.id', '=', 'r_usabilities.group_id')
                ->joinTranslations($builder->getModel()->translatable)
                ->joinTranslatable('r_enums', 'name as group_name');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
