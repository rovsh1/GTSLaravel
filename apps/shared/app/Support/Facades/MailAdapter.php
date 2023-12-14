<?php

namespace App\Shared\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Sdk\Shared\Contracts\Adapter\MailAdapterInterface;
use Sdk\Shared\Dto\Mail\SendMessageRequestDto;

/**
 * @method static bool send(SendMessageRequestDto $requestDto)
 * @method static bool sendTo(string $to, string $subject, string $body)
 */
class MailAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MailAdapterInterface::class;
    }
}
