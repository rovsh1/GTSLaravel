<?php

namespace Module\Services\MailManager\Port\Controllers;

use Custom\Framework\PortGateway\Request;
use Module\Services\MailManager\Domain\ValueObject\MailTemplateEnum;
use Module\Services\MailManager\Infrastructure\Model\QueueMessage;

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
