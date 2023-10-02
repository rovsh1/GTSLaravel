<?php

namespace Module\Booking\Common\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
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
