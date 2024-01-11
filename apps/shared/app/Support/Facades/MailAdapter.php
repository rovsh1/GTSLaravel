<?php

namespace App\Shared\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Sdk\Shared\Dto\Mail\MailMessageDto;
use Shared\Contracts\Adapter\MailAdapterInterface;

/**
 * @method static bool send(MailMessageDto $messageDto)
 * @method static bool sendTo(string $to, string $subject, string $body, array $attachments = [])
 */
class MailAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MailAdapterInterface::class;
    }
}
