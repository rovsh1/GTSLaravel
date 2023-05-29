<?php

namespace App\Admin\Models\ServiceProvider;

use App\Admin\Enums\ServiceProvider\ServiceTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Service extends Model
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'name%'];

    protected $table = 'service_provider_services';

    protected $fillable = [
        'provider_id',
        'name',
        'type',
    ];

    protected $casts = [
        'provider_id' => 'int',
        'type' => ServiceTypeEnum::class,
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->orderBy('name');
        });
    }

    public function getForeignKey()
    {
        return 'service_id';
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
