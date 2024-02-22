<?php

namespace App\Hotel\Models\Reference;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class LandmarkType extends Model
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected $table = 'r_landmark_types';

    protected $fillable = [
        'alias',
        'name',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->orderBy('r_landmark_types.name', 'asc');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
