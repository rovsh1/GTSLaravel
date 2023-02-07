<?php

namespace GTS\Integration\Traveline\Infrastructure\Adapter;

use GTS\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface;
use GuzzleHttp\ClientInterface;

class TravelineAdapter implements TravelineAdapterInterface
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
