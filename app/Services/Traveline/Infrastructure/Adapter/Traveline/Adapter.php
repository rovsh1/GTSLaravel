<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter\Traveline;

use GuzzleHttp\ClientInterface;

class Adapter implements AdapterInterface
{
    public function __construct(private ClientInterface $httpClient) {}

    public function sendReservationNotification(): void
    {
        $url = config('services.traveline.notifications_url');
        if (empty($url)) {
            return;
        }
        $this->httpClient->request('GET', $url);
    }
}
