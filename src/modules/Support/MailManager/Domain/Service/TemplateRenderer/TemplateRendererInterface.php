<?php

namespace Module\Support\MailManager\Domain\Service\TemplateRenderer;

use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;

interface TemplateRendererInterface
{
    public function render(TemplateInterface $mailTemplate, DataInterface $data): string;
}