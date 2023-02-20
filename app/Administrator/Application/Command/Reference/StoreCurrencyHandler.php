<?php

namespace GTS\Administrator\Application\Command\Reference;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

use GTS\Administrator\Application\Dto\Administrator\AdministratorDto;
use GTS\Administrator\Domain\Repository\CurrencyRepositoryInterface;

class StoreCurrencyHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository
    ) {}

    public function handle(CommandInterface $command)
    {
        // Todo здесь в комманде поле $data
        //dd((array)$command);
        $this->currencyRepository->store($command);
    }
}
