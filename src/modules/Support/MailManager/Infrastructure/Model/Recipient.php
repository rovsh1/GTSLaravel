<?php

namespace Module\Support\MailManager\Infrastructure\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @method static self whereTemplate(string $template)
 * @method Collection get()
 * @property string template
 * @property string recipient_type
 * @property string|null recipient_id
 */
class Recipient extends BaseModel
{
    protected $table = 's_mail_recipients';

    public function scopeWhereTemplate(Builder $builder, string $template): void
    {
        $builder->where('template', $template)
            ->orWhere('template', '*');
    }
}
