<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter\Traveline;

use GTS\Services\Traveline\Domain\Adapter\Traveline\AdapterInterface;

use GuzzleHttp\ClientInterface;

class Adapter implements AdapterInterface
{
    public function __construct(
        private ClientInterface $httpClient,
        private string          $notificationsUrl
    ) {}

    public function sendReservationNotification(): void
    {
        $this->httpClient->request('GET', $this->notificationsUrl);
    }
}
