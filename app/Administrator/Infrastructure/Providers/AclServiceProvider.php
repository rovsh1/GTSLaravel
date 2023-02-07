<?php

namespace GTS\Administrator\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\ServiceProvider;
use GTS\Administrator\Domain\Repository\AclRepositoryInterface;
use GTS\Administrator\Domain\Service\Acl;
use GTS\Administrator\Infrastructure\Repository\AclRepository;

class AclServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AclRepositoryInterface::class, AclRepository::class);

        $this->app->singleton(Acl\AccessControlInterface::class, function () {
            $acl = new Acl\AccessControl();

            $this->bootResources($acl);

            return $acl;
        });

        $this->app->alias(Acl\AccessControlInterface::class, 'acl');
    }

    private function bootResources($acl)
    {
        $resources = $acl->resources();

        $this->addResource($resources, 'reservation');
        $this->addResource($resources, 'invoice');
        $this->addResource($resources, 'hotel', ['create', 'read', 'update', 'delete', 'quotes']);
        $this->addResource($resources, 'client');
        $this->addResource($resources, 'acl');
        $this->addResource($resources, 'administrator');
        $this->addResource($resources, 'user');
        $this->addResource($resources, 'country');
        $this->addResource($resources, 'city');
        $this->addResource($resources, 'currency');

//        foreach (app('modules') as $module) {
//            $facadeClass = $module->namespace('Infrastructure\Facade\Acl\Facade');
//        }
    }

    private function addResource($resources, string $resourceId, array $permissions = ['create', 'read', 'update', 'delete'])
    {
        $resources->add(new Acl\Resource($resourceId, $permissions));
    }
}
