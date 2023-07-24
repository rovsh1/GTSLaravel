<?php

namespace Module\Support\MailManager\Port\Controllers;

use Module\Support\MailManager\Application\Command\SendQueued;
use Module\Support\MailManager\Application\Command\SendSync;
use Module\Support\MailManager\Application\Command\SendTemplateQueued;
use Module\Support\MailManager\Application\Dto\MailMessageDto;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\PortGateway\Request;

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
            new SendQueued(
                $this->sendRequestToDto($request),
                (int)$request->priority,
                $request->context
            )
        );
    }

    public function sendSync(Request $request)
    {
        $this->validateSendRequest($request);

        return $this->commandBus->execute(
            new SendSync($this->sendRequestToDto($request), $request->context)
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
            'context' => 'nullable|array',
        ]);
    }
}
