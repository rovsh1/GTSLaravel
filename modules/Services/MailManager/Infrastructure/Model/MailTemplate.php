<?php

namespace Module\Services\MailManager\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;

class MailTemplate extends BaseModel
{
    protected $table = 's_mail_templates';

    protected $fillable = [
        'key',
        'language',
        'country_id',
        'subject',
        'body',
    ];
}
