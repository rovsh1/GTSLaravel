<?php

namespace Module\Pricing\CurrencyRate\Port\Controllers;

use DateTime;
use Module\Pricing\CurrencyRate\Application\Command\GetRate;
use Module\Pricing\CurrencyRate\Application\Command\UpdateRates;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\PortGateway\Request;

class MainController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function rate(Request $request): float
    {
        $request->validate([
            'country' => 'required|string',
            'currency' => 'required|string'
        ]);

        /** @var CurrencyRate $rate */
        $rate = $this->commandBus->execute(
            new GetRate(
                CountryEnum::from($request->country),
                CurrencyEnum::from($request->currency)
            )
        );

        return $rate->rate();
    }

    public function update(Request $request): void
    {
        $request->validate([
            'date' => 'nullable|date'
        ]);

        $date = self::dateFactory($request->date);

        foreach (CountryEnum::cases() as $case) {
            $this->commandBus->execute(
                new UpdateRates($case, $date)
            );
        }
    }

    public function updateCountry(Request $request): void
    {
        $request->validate([
            'country' => 'required|string',
            'date' => 'nullable|date'
        ]);

        $rates = $this->commandBus->execute(
            new UpdateRates(CountryEnum::from($request->country), self::dateFactory($request->date))
        );
    }

    private static function dateFactory(null|string|DateTime $date): ?DateTime
    {
        if (is_string($date)) {
            return new DateTime($date);
        } elseif (is_null($date)) {
            return null;
        } else {
            return $date;
        }
    }
}
