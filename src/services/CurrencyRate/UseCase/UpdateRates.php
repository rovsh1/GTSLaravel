<?php

namespace Services\CurrencyRate\UseCase;

use DateTime;
use DateTimeInterface;
use Exception;
use Services\CurrencyRate\Service\RateManager;
use Services\CurrencyRate\ValueObject\CountryEnum;
use Throwable;

class UpdateRates
{
    public function __construct(
        private readonly RateManager $rateManager,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(DateTimeInterface $date): void
    {
        $date = self::dateFactory($date);

        foreach (CountryEnum::cases() as $case) {
            try {
                $this->rateManager->update($case, $date);
            } catch (Throwable $e) {
                throw new Exception('Currency [' . $case->value . '] update failed', 0, $e);
            }
        }
    }

    private static function dateFactory(null|string|DateTimeInterface $date): ?DateTimeInterface
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