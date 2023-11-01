<?php

namespace Module\Generic\Notification\Infrastructure\Service;

use Module\Generic\Notification\Domain\MailSettings\Dto\MailDataDto;
use Module\Generic\Notification\Domain\MailSettings\MailSettings;
use Module\Generic\Notification\Domain\MailSettings\Service\RecipientResolverInterface;
use Module\Generic\Notification\Domain\MailSettings\Dto\RecipientListDto;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\RecipientInterface;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\RecipientTypeEnum;

class MailRecipientResolver implements RecipientResolverInterface
{
    public function __construct(
        private readonly MailRecipientBuilder $recipientBuilder
    ) {
    }

    public function resolve(MailSettings $settings, MailDataDto $data): RecipientListDto
    {
        foreach ($settings->recipients() as $recipient) {
            $this->resolveRecipient($recipient, $data);
        }

        return $this->recipientBuilder->build();
    }

    private function resolveRecipient(RecipientInterface $recipient, MailDataDto $data): void
    {
        switch ($recipient->type()) {
            case RecipientTypeEnum::ADMINISTRATOR:
                $this->recipientBuilder->addAdministrator($recipient->administratorId());
                break;
            case RecipientTypeEnum::ADMINISTRATOR_GROUP:
                $this->recipientBuilder->addAdministratorGroup($recipient->groupId());
                break;
            case RecipientTypeEnum::CLIENT_CONTACTS:
                if ($data->clientId) {
                    $this->recipientBuilder->addClientContacts($data->clientId);
                }
                break;
            case RecipientTypeEnum::CLIENT_MANAGERS:
                throw new \Exception('To be implemented');
            case RecipientTypeEnum::HOTEL_CONTACTS:
                if ($data->hotelId) {
                    $this->recipientBuilder->addHotelContacts($data->hotelId);
                }
                break;
            case RecipientTypeEnum::HOTEL_ADMINISTRATORS:
                if ($data->hotelId) {
                    $this->recipientBuilder->addHotelUsers($data->hotelId);
                }
                break;
            case RecipientTypeEnum::EMAIL_ADDRESS:
                $this->recipientBuilder->addEmail($recipient->email(), $recipient->presentation());
                break;
            case RecipientTypeEnum::CUSTOMER:
                throw new \Exception('To be implemented');
        }
    }
}
