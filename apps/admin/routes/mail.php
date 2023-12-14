<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('mail-queue')
    ->post('/{uuid}/retry', Controllers\Administration\MailQueueController::class . '@retry', 'update', 'retry')
    ->post('/{uuid}/resend', Controllers\Administration\MailQueueController::class . '@resend', 'update', 'resend')
    ->get('/send-test', Controllers\Administration\MailQueueController::class . '@sendTest', 'read', 'send-test');
