<?php

namespace Module;

use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Domain\Service\SerializerInterface;
use Module\Shared\Domain\Service\TranslatorInterface;
use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;
use Sdk\Module\Foundation\SharedKernel as BaseKernel;

class SharedKernel extends BaseKernel
{
    protected array $applicationBindings = [
        PortGatewayInterface::class,
        //ModulesBusInterface::class
        TranslatorInterface::class,
        SerializerInterface::class,
        CurrencyRateAdapterInterface::class
    ];

    protected function registerSharedBindings(): void {}
}
