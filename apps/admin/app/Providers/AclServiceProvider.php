<?php

namespace App\Admin\Providers;

use App\Admin\Services\Acl;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use GTS\Administrator\Domain\Service\Acl;
use Module\Administrator\Domain\Repository\AclRepositoryInterface;

class AclServiceProvider extends ServiceProvider
{
    public function register()
    {
        //$this->app->singleton(AclRepositoryInterface::class, AclRepository::class);

        $this->app->singleton(AccessControlInterface::class, function () {
            $acl = new \App\Admin\Services\Acl\AccessControl();

            $this->bootResources($acl);

            return $acl;
        });

        $this->app->alias(AccessControlInterface::class, 'acl');
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
        $resources->add(new \App\Admin\Services\Acl\Resource($resourceId, $permissions));
    }
}
