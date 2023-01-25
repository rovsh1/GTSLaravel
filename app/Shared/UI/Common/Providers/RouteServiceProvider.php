<?php

namespace GTS\Shared\UI\Common\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {


    protected function getModulesRoutes($port): array {
        $routes = [];
        foreach (app('modules')->paths() as $path) {
            $route = $path . '/UI/' . $port . '/routes.php';
            if (file_exists($route)) {
                $routes[] = $route;
            }
        }
        return $routes;
    }
}
