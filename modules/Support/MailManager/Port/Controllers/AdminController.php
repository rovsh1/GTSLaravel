<?php

namespace Module\Support\MailManager\Port\Controllers;

use Module\Support\MailManager\Domain\ValueObject\MailTemplateEnum;
use Module\Support\MailManager\Infrastructure\Model\QueueMessage;
use Sdk\Module\PortGateway\Request;

class AdminController
{
    public function __construct()
    {
    }

    public function templatesList(Request $request): array
    {
        return array_map(function (MailTemplateEnum $case) {
            return $case->name;
        }, MailTemplateEnum::cases());
    }

    public function getQueue(Request $request)
    {
        return QueueMessage::query();
    }
}
