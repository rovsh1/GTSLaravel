<?php

namespace Sdk\Shared\Contracts\Service;

use Sdk\Shared\Enum\Context\ContextChannelEnum;
use Sdk\Shared\Enum\SourceEnum;

interface ApplicationContextInterface
{
    public function requestId(): string;

    public function channel(): ?ContextChannelEnum;

    public function source(): ?SourceEnum;

    public function apiKey(): ?string;

    public function userId(): ?int;

    public function administratorId(): ?int;

    public function setChannel(ContextChannelEnum $channel): void;

    public function setSource(SourceEnum $source): void;

    public function setApiKey(string $apiKey): void;

    public function setModule(string $module): void;

    public function setHttpHost(string $host): void;

    public function setHttpUrl(string $url): void;

    public function setHttpMethod(string $method): void;

    public function setUserIp(string $userIp): void;

    public function setUserAgent(string $userAgent): void;

    public function setUserId(int $id): void;

    public function setAdministrator(int $id, string $presentation): void;

    public function setEntity(string $class, int $id): void;

    public function addTag(string $tag): void;

    public function setException(\Throwable $exception): void;

    public function setErrorCode(int $code): void;

    public function toArray(): array;
}