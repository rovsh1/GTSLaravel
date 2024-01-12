<?php

namespace Pkg\Supplier\Traveline\Adapters;

use GuzzleHttp\ClientInterface;
use Supplier\Traveline\Domain\Adapter\TravelineAdapterInterface;

class TravelineAdapter implements TravelineAdapterInterface
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly string $notificationsUrl
    ) {}

    public function sendReservationNotification(): void
    {
        $this->httpClient->request('GET', $this->notificationsUrl);
    }
}
