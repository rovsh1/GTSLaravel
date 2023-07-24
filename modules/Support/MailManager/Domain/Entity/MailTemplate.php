<?php

namespace Module\Support\MailManager\Domain\Entity;

class MailTemplate
{
    private string $mail;

    private array $recipients;

    private array $data = [];
}