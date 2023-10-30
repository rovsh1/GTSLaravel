<?php

namespace Module\Support\MailManager\Domain\Service\DataBuilder;

use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;

interface DataBuilderInterface
{
    public function build(
        TemplateInterface $template,
        DataDtoInterface $dataDto,
        RecipientInterface $recipient
    ): DataInterface;
}
