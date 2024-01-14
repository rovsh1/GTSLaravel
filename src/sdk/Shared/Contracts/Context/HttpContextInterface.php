<?php

namespace Sdk\Shared\Contracts\Context;

interface HttpContextInterface
{
    public function setHttpHost(string $host): void;

    public function setHttpUrl(string $url): void;

    public function setHttpMethod(string $method): void;

    public function setUserIp(string $userIp): void;

    public function setUserAgent(string $userAgent): void;
}