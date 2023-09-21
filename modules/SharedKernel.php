<?php

namespace Module;

use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Contracts\Adapter\LocaleDictionaryAdapterInterface;
use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Domain\Service\ApplicationConstantsInterface;
use Module\Shared\Domain\Service\ApplicationContextInterface;
use Module\Shared\Domain\Service\CompanyRequisitesInterface;
use Module\Shared\Domain\Service\SafeExecutorInterface;
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
        SafeExecutorInterface::class,
        CurrencyRateAdapterInterface::class,
        FileStorageAdapterInterface::class,
        ApplicationContextInterface::class,
        ApplicationConstantsInterface::class,
        CompanyRequisitesInterface::class,
        LocaleDictionaryAdapterInterface::class,
    ];

    protected function registerSharedBindings(): void
    {
    }
}
