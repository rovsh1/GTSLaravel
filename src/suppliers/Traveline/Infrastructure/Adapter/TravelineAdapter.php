<?php

namespace Supplier\Traveline\Infrastructure\Adapter;

use GuzzleHttp\ClientInterface;
use Supplier\Traveline\Domain\Adapter\TravelineAdapterInterface;

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
