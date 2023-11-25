<?php

namespace Sdk\Shared\Support\ApplicationContext\Concerns;

use Sdk\Shared\Enum\Context\ContextChannelEnum;
use Sdk\Shared\Enum\SourceEnum;

trait CommonContextTrait
{
    public function setChannel(ContextChannelEnum $channel): void
    {
        $this->set('channel', $channel->value);
    }

    public function channel(): ?ContextChannelEnum
    {
        return ContextChannelEnum::tryFrom($this->get('channel'));
    }

    public function setSource(SourceEnum $channel): void
    {
        $this->set('source', $channel->value);
    }

    public function source(): ?SourceEnum
    {
        return SourceEnum::tryFrom($this->get('source'));
    }

    public function setApiKey(string $key): void
    {
        $this->set('apiKey', $key);
    }

    public function apiKey(): ?string
    {
        return $this->get('apiKey');
    }
}
