<?php

namespace Module;

use App\Core\Support\Adapters\ConstantAdapter;
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
        ConstantAdapter::class,
    ];

    protected function registerSharedBindings(): void {}
}
