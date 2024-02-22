<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\Service;

use Illuminate\Support\Facades\App;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Sdk\Booking\ValueObject\ClientId;

class ClientLocaleContext
{
    public function __construct(
        private readonly ClientAdapterInterface $clientAdapter
    ) {}

    public function executeInClientLocale(ClientId $clientId, callable $handler): mixed
    {
        $client = $this->clientAdapter->find($clientId->value());
        $clientLocale = $client->language->value;

        $currentLocale = App::currentLocale();
        App::setLocale($clientLocale);

        $result = $handler();

        App::setLocale($currentLocale);

        return $result;
    }
}
