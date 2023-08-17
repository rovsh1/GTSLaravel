<?php

namespace Module\Support\MailManager\Domain\Service\DataBuilder\Support;

use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\RecipientDataDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\DataDto\SenderDataDto;
use ReflectionClass;

abstract class AbstractData
{
    protected RecipientDataDto $recipient;

    protected SenderDataDto $sender;

    public function setRecipientDto(RecipientDataDto $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function setSenderDto(SenderDataDto $sender): void
    {
        $this->sender = $sender;
    }

    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);

        $array = [];
        foreach ($reflection->getProperties() as $p) {
            $array[$p->name] = $this->{$p->name};
        }

        return $array;
    }
}