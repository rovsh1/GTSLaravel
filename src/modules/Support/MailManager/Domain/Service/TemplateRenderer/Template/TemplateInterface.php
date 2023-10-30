<?php

namespace Module\Support\MailManager\Domain\Service\TemplateRenderer\Template;

interface TemplateInterface
{
    public function key(): string;

    public function subject(): string;
}