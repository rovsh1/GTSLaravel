<?php

namespace Module\Services\MailManager\Domain\Entity;

interface MailTemplateInterface
{
    public function key(): string;

    public function category(): string;

    public function description(): string;
}