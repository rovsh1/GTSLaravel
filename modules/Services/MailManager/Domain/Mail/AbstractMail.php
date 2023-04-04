<?php

namespace Module\Services\MailManager\Domain\Mail;

abstract class AbstractMail implements MailInterface
{
    public function __construct(array $data) {}

    public function key(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}