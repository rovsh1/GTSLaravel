<?php

namespace Module\Support\MailManager\Domain\Service\DataBuilder\Data;

use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\RecipientDataDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\SenderDataDto;

interface DataInterface
{
    public function setRecipientDto(RecipientDataDto $recipient): void;

    public function setSenderDto(SenderDataDto $sender): void;

    public function toArray(): array;
}