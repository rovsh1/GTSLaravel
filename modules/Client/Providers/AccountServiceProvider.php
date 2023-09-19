<?php

namespace Module\Client\Providers;

use Module\Client\Domain\Account\Repository\AccountRepositoryInterface;
use Module\Client\Domain\Account\Repository\BalanceRepositoryInterface;
use Module\Client\Infrastructure\Repository\AccountRepository;
use Module\Client\Infrastructure\Repository\BalanceRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->singleton(BalanceRepositoryInterface::class, BalanceRepository::class);
    }
}
