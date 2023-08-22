<?php

namespace Module\Shared\Infrastructure\Service\ApplicationContext\Concerns;

trait HttpRequestContextTrait
{
    public function setHttpHost(string $host): void
    {
        $this->set('http.host', $host);
    }

    public function setHttpUrl(string $url): void
    {
        $this->set('http.url', $url);
    }

    public function setHttpMethod(string $method): void
    {
        $this->set('http.method', $method);
    }

    public function setUserIp(string $userIp): void
    {
        $this->set('http.userIp', $userIp);
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->set('http.userAgent', $userAgent);
    }
}
