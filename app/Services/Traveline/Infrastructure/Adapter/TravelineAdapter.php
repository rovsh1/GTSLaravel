<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter;

use GTS\Services\Traveline\Domain\Adapter\TravelineAdapterInterface;
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
