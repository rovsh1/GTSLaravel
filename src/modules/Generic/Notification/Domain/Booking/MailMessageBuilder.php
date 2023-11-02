<?php

namespace Module\Generic\Notification\Domain\Booking;

use Module\Generic\Notification\Domain\MailSettings\Dto\MailDataDto;
use Module\Generic\Notification\Domain\MailSettings\Repository\MailSettingsRepositoryInterface;
use Module\Generic\Notification\Domain\MailSettings\Service\RecipientResolverInterface;
use Module\Generic\Notification\Domain\Shared\Adapter\MailAdapterInterface;
use Module\Generic\Notification\Domain\Shared\Enum\NotificationTypeEnum;
use Module\Support\MailManager\Application\Dto\MailMessageDto;
use Module\Support\MailManager\Application\RequestDto\SendMessageRequestDto;

class MailMessageBuilder
{
    public function __construct(
        private readonly MailSettingsRepositoryInterface $mailSettingsRepository,
        private readonly RecipientResolverInterface $recipientResolver,
        private readonly MailAdapterInterface $mailAdapter,
    ) {
    }

    public function build(NotificationTypeEnum $notificationType, $bookingData): void
    {
        $mailSettings = $this->mailSettingsRepository->find($notificationType);

        $mailData = new MailDataDto(
            hotelId: $bookingData->hotelId,
            clientId: $bookingData->clientId
        );
        $recipientList = $this->recipientResolver->resolve($mailSettings, $mailData);
        if ($recipientList->isEmpty()) {
            return;
        }

        foreach ($recipientList->recipients as $recipient) {
            $this->mailAdapter->send(
                new SendMessageRequestDto(
                    new MailMessageDto(
                        to: [$recipient->email],
                        subject: $subject,
                        body: $body,
                        from: [$from]
                    )
                )
            );
        }
    }
}