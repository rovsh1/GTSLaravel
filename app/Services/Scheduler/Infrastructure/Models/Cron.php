<?php

namespace GTS\Services\Scheduler\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class Cron extends Model
{
    public $timestamps = false;

    protected $table = 's_crontab';

    protected $fillable = [
        'enabled',
        'time',
        'user',
        'command',
        'arguments',
        'description',
        'last_executed',
        'last_status',
        'last_log'
    ];

    protected $casts = [
        'last_executed' => 'datetime:Y-m-d H:i:s'
    ];

    public function __toString()
    {
        return (string)$this->command;
    }
}
