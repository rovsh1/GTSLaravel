<?php

namespace Shared\Models;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\HasTranslations;

class CompanyRequisite extends Model
{
    use HasTranslations;
    use HasQuicksearch;

    public $timestamps = false;

    protected $table = 's_company_requisites';

    protected $quicksearch = ['key%', 's_company_requisites_translation.%value%'];

    protected $translatable = ['value'];

    protected $fillable = [
        'key',
        'value'
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('s_company_requisites.*')
                ->joinTranslations();
        });
    }

    public function __toString(): string
    {
        return (string)$this->key;
    }
}
