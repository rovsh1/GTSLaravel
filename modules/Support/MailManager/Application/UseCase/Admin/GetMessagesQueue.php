<?php

namespace Module\Support\MailManager\Application\UseCase\Admin;

use Illuminate\Database\Eloquent\Builder;
use Module\Support\MailManager\Infrastructure\Model\QueueMessage;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetMessagesQueue implements UseCaseInterface
{
    public function execute(): Builder
    {
        return QueueMessage::query();
    }
}