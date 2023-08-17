<?php

namespace Module\Support\MailManager\Application\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataBuilderInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\RecipientDataDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\SenderDataDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\RecipientAddressResolverInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\RecipientsFinderInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\TemplateRendererInterface;
use Module\Support\MailManager\Domain\ValueObject\MailBody;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;

class MailTemplateSender
{
    public function __construct(
        private readonly QueueManagerInterface $queueManager,
        private readonly RecipientsFinderInterface $recipientsFinder,
        private readonly DataBuilderInterface $dataBuilder,
        private readonly TemplateRendererInterface $templateRenderer,
        private readonly RecipientAddressResolverInterface $addressResolver,
    ) {
    }

    public function send(TemplateInterface $template, DataDtoInterface $dataDto, array $context): void
    {
        $recipients = $this->recipientsFinder->findByTemplate($template, $dataDto);
        foreach ($recipients as $recipient) {
            $this->sendToRecipient($recipient, $template, $dataDto, $context);
        }
    }

    private function sendToRecipient(
        RecipientInterface $recipient,
        TemplateInterface $template,
        DataDtoInterface $dataDto,
        array $context
    ): void {
        $to = $this->addressResolver->resolve($recipient);
        if ($to->isEmpty()) {
            //@todo log it

            return;
        }

        $data = $this->dataBuilder->build($template, $dataDto, $recipient);
        $data->setSenderDto($this->makeSenderDto());
        $data->setRecipientDto(new RecipientDataDto('dsd'));
        $body = $this->templateRenderer->render($template, $data);

        $this->queueManager->sendSync(
            new Mail(
                MailId::createNew(),
                QueueMailStatusEnum::WAITING,
                $to,
                $template->subject(),
                new MailBody($body),
            ),
            $context
        );
//        $this->queueManager->push(
//            new Mail(
//                MailId::createNew(),
//                QueueMailStatusEnum::WAITING,
//                $to,
//                $template->subject(),
//                new MailBody($body),
//            ),
//            $priority = 0,
//            $context
//        );
    }

    private function makeSenderDto(): SenderDataDto
    {
        return new SenderDataDto(
            presentation: 'asdsd',
            postName: 'ds',
            email: 'we',
            phone: 'dsd'
        );
    }
}