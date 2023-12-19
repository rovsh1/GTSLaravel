<?php

namespace App\Admin\Models\Reference;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class CancelReason extends Model
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected $table = 'r_cancel_reasons';

    protected $fillable = [
        'name',
        'has_description'
    ];

    protected $casts = [
        'has_description' => 'boolean'
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->orderBy('r_cancel_reasons.name', 'asc');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
