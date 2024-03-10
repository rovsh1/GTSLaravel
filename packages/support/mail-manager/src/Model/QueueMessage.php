<?php

namespace Pkg\MailManager\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class QueueMessage extends BaseModel
{
    use HasUuids;
    use HasQuicksearch;

    protected $keyType = 'string';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $table = 's_mail_queue';

    protected array $quicksearch = ['%subject%'];

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
        'payload' => 'array',
        'priority' => 'int',
        'attempts' => 'int',
        'status' => 'int',
        'failed_at' => 'datetime:Y-m-d H:i:s',
        'sent_at' => 'datetime:Y-m-d H:i:s',
        'context' => 'array',
    ];
}
