<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Pricing\CurrencyRate\Domain\Adapter\LoggerAdapterInterface;
use Module\Pricing\CurrencyRate\Domain\Repository\CacheRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\Repository\DatabaseRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Module\Pricing\CurrencyRate\Infrastructure\Api\ApiFactory;
use Throwable;
use Exception;

class UpdateRatesHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $repository,
        private readonly CacheRepositoryInterface $cache,
        private readonly LoggerAdapterInterface $logger,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(CommandInterface|GetRate $command): CurrencyRatesCollection
    {
        try {
            $api = ApiFactory::fromCountry($command->country);
            $rates = $api->getRates();
            foreach ($rates as $rate) {
                $this->cache->setRate($command->country, $rate, $command->date);
                $this->repository->setRate($command->country, $rate, $command->date);
            }
            return $rates;
        } catch (Throwable $e) {
            throw new Exception('Currency [' . $command->country->value . '] update failed', 0, $e);
        }
    }
}
