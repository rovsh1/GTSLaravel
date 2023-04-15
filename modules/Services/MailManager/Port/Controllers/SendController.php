<?php

namespace Module\Services\MailManager\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\PortGateway\Request;
use Module\Services\MailManager\Application\Command\SendQueued;
use Module\Services\MailManager\Application\Command\SendSync;
use Module\Services\MailManager\Application\Dto\MailMessageDto;

class SendController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        return $this->commandBus->execute(
            new SendQueued(
                new MailMessageDto(
                    [$request->to],
                    $request->subject,
                    $request->body,
                )
            )
        );
    }

    public function sendTemplate(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'template' => 'required|string',
            //'data' => 'required|string',
        ]);

        return $this->commandBus->execute(
            new SendTemplateQueued(
                $request->template
            )
        );
    }
}
