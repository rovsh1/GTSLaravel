<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('mail-template')
    ->get(
        '/{template}/send-test',
        Controllers\Administration\MailTemplateController::class . '@sendTest',
        'read',
        'send-test'
    );

AclRoute::for('mail-queue')
    ->post('/{uuid}/retry', Controllers\Administration\MailQueueController::class . '@retry', 'update', 'retry')
    ->post('/{uuid}/resend', Controllers\Administration\MailQueueController::class . '@resend', 'update', 'resend');
