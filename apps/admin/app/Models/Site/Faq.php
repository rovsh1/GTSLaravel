<?php

namespace App\Admin\Models\Site;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

class Faq extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['%question%', '%answer%'];

    protected array $translatable = ['question', 'answer'];

    protected $table = 'site_faq';

    protected $fillable = [
        'question',
        'answer',
        'type'
    ];

    protected $attributes = [
        //@todo выяснить, что за тип. Сейчас в базе всегда 2
        'type' => 2
    ];

    protected $casts = [
        'type' => 'int'
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('site_faq.*')
                ->joinTranslations();
        });
    }

    public function __toString()
    {
        return (string)$this->question;
    }
}
