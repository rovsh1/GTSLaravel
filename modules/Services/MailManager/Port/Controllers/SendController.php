<?php

namespace Module\Services\MailManager\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\PortGateway\Request;
use Module\Services\MailManager\Application\Command\SendQueued;
use Module\Services\MailManager\Application\Command\SendSync;
use Module\Services\MailManager\Application\Command\SendTemplateQueued;
use Module\Services\MailManager\Application\Dto\MailMessageDto;

class SendController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function send(Request $request)
    {
        $this->validateSendRequest($request);

        return $this->commandBus->execute(
            new SendQueued($this->sendRequestToDto($request))
        );
    }

    public function sendSync(Request $request)
    {
        $this->validateSendRequest($request);

        return $this->commandBus->execute(
            new SendSync($this->sendRequestToDto($request))
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

    private function sendRequestToDto(Request $request): MailMessageDto
    {
        return new MailMessageDto(
            [$request->to],
            $request->subject,
            $request->body,
        );
    }

    private function validateSendRequest(Request $request): void
    {
        $request->validate([
            'to' => 'required|string',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);
    }
}
