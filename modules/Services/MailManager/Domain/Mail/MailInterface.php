<?php

namespace Module\Services\MailManager\Domain\Mail;

interface MailInterface
{
    public function key(): string;

    public function category(): string;

    public function description(): string;
}