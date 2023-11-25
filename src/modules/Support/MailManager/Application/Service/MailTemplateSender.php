<?php

namespace Module\Support\MailManager\Application\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataBuilderInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\RecipientDataDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\SenderDataDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\AddressResolverInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\RecipientsFinderInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\TemplateRendererInterface;
use Module\Support\MailManager\Domain\ValueObject\AddressList;
use Module\Support\MailManager\Domain\ValueObject\MailBody;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;

class MailTemplateSender
{
    public function __construct(
        private readonly QueueManagerInterface $queueManager,
        private readonly RecipientsFinderInterface $recipientsFinder,
        private readonly DataBuilderInterface $dataBuilder,
        private readonly TemplateRendererInterface $templateRenderer,
        private readonly AddressResolverInterface $addressResolver,
        private readonly CompanyRequisitesInterface $companyRequisites,
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
        $toList = $this->addressResolver->resolve($recipient);
        if (null === $toList) {
            //@todo log it

            return;
        }

        $data = $this->dataBuilder->build($template, $dataDto, $recipient);
        $data->setSenderDto($this->makeSenderDto($context));

        foreach ($toList as $address) {
            $data->setRecipientDto(new RecipientDataDto($address->name() ?? $address->email()));
            $body = $this->templateRenderer->render($template, $data);

            $this->queueManager->sendSync(
                new Mail(
                    MailId::createNew(),
                    QueueMailStatusEnum::WAITING,
                    new AddressList([$address]),
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
    }

    private function makeSenderDto(array $context): SenderDataDto
    {
        return new SenderDataDto(
            presentation: $this->companyRequisites->name(),
            postName: null,
            email: $this->companyRequisites->email(),
            phone: $this->companyRequisites->phone()
        );
    }
}