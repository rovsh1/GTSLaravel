<?php

namespace Module\Support\MailManager\Infrastructure\Service\DataBuilder;

use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataBuilderInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\BookingTemplateInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;
use Sdk\Module\Contracts\ModuleInterface;

class CommonDataBuilder implements DataBuilderInterface
{
    public function __construct(private readonly ModuleInterface $container)
    {
    }

    public function build(
        TemplateInterface $template,
        DataDtoInterface $dataDto,
        RecipientInterface $recipient
    ): DataInterface {
        if ($template instanceof BookingTemplateInterface) {
            return $this->container->make(BookingDataBuilder::class)->build($template, $dataDto, $recipient);
        } else {
            throw new \LogicException('Data builder for ' . $template::class . ' not implemented');
        }
    }
}