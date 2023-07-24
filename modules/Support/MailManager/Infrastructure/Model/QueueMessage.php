<?php

namespace Module\Support\MailManager\Infrastructure\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as BaseModel;

class QueueMessage extends BaseModel
{
    use HasUuids;

    protected $keyType = 'string';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $table = 's_mail_queue';

    protected $fillable = [
        'uuid',
        'subject',
        'payload',
        'context',
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
        'context' => 'array',
    ];
}
