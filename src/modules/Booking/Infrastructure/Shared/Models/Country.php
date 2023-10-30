<?php

namespace Module\Booking\Infrastructure\Shared\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

class Country extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected array $translatable = ['name'];

    protected $table = 'r_countries';

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_countries.*')
                ->joinTranslations();
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
