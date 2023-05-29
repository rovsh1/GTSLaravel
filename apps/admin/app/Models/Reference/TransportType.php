<?php

namespace App\Admin\Models\Reference;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

class TransportType extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected array $translatable = ['name'];

    protected $table = 'r_transport_types';

    protected $fillable = [
        'name',
        'color',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_transport_types.*')
                ->joinTranslations()
                ->orderBy('name', 'asc');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
