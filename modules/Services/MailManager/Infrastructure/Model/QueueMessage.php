<?php

namespace Module\Services\MailManager\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;

class QueueMessage extends BaseModel
{
    protected $table = 's_mail_queue';

    protected $fillable = [
        'uuid',
        'payload',
        'priority',
        'status',
        'attempts',
        'failed_at',
        'sent_at',
    ];

    protected $casts = [
        'priority' => 'int',
        'attempts' => 'int',
        'status' => 'int',
        'failed_at' => 'datetime:Y-m-d H:i:s',
        'sent_at' => 'datetime:Y-m-d H:i:s',
    ];
}
