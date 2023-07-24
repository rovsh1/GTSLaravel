<?php

namespace Module\Support\MailManager\Domain\MailTemplate;

use Module\Support\MailManager\Domain\Entity\MailTemplateInterface;

abstract class AbstractMailTemplate implements MailTemplateInterface
{
    public function __construct(array $data) {}

    public function key(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}