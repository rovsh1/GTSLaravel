<?php

namespace Module\Support\MailManager\Application\UseCase\Admin;

use Module\Support\MailManager\Domain\ValueObject\MailTemplateEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetTemplatesList implements UseCaseInterface
{
    public function execute(): array
    {
        return array_map(function (MailTemplateEnum $case) {
            return $case->name;
        }, MailTemplateEnum::cases());
    }
}