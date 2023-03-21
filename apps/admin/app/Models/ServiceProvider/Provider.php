<?php

namespace App\Admin\Models\ServiceProvider;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'name%'];

    protected $table = 'service_providers';

    protected $fillable = [
        'name',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {});
    }

    public function getForeignKey()
    {
        return 'provider_id';
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
