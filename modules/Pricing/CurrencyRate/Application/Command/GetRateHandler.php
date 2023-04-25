<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Pricing\CurrencyRate\Domain\Adapter\LoggerAdapterInterface;
use Module\Pricing\CurrencyRate\Domain\Repository\CacheRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\Repository\DatabaseRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Module\Pricing\CurrencyRate\Infrastructure\Api\ApiFactory;
use Throwable;
use Exception;

class GetRateHandler implements CommandHandlerInterface
{
    private const FAILED_CACHE_TTL = 60 * 5;

    public function __construct(
        private readonly DatabaseRepositoryInterface $repository,
        private readonly CacheRepositoryInterface $cache,
        private readonly LoggerAdapterInterface $logger,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(CommandInterface|GetRate $command): ?CurrencyRate
    {
        if ($command->country->currency() === $command->currency) {
            return new CurrencyRate($command->currency, 1.0, 1);
        }

        $rate = $this->cache->getRate($command->country, $command->currency, $command->date);
        if ($rate) {
            return $rate;
        }

        $rate = $this->repository->getRate($command->country, $command->currency, $command->date);
        if ($rate) {
            $this->cache->setRate($command->country, $rate, $command->date);
            return $rate;
        }

        try {
            $api = ApiFactory::fromCountry($command->country);
            $rates = $api->getRates();
            foreach ($rates as $rate) {
                $this->cache->setRate($command->country, $rate, $command->date);
                $this->repository->setRate($command->country, $rate, $command->date);
            }

            if ($rates->has($command->currency)) {
                return $rates->get($command->currency);
            }
        } catch (Throwable $e) {
            //dd($e);
        }

        //TODO report warning notification (logManager)
        //$this->logger->exception();

        $rate = $this->repository->getLastFilledRate($command->country, $command->currency, $command->date);
        if ($rate) {
            $this->cache->setRate($command->country, $rate, $command->date, self::FAILED_CACHE_TTL);
            return $rate;
        }

        throw new Exception(
            'Currency [' . $command->country->value . '-' . $command->currency->value . '] rate undefined'
        );
    }
}
