<?php

namespace Sdk\Module\Foundation\Support;

use LogicException;
use Sdk\Module\Foundation\Module;
use Sdk\Module\Foundation\ModulesManager;

class ModulesLoader
{
    private ModulesManager $moduleManager;

    private SharedContainer $sharedContainer;

    public function __construct(
        ModulesManager $moduleManager,
        SharedContainer $sharedContainer
    ) {
        $this->moduleManager = $moduleManager;
        $this->sharedContainer = $sharedContainer;
    }

    public function load(array $modulesConfig): void
    {
        foreach ($modulesConfig as $name => $config) {
            if (isset($config['enabled']) && $config['enabled'] === false) {
                continue;
            }
            $this->registerModule($name, $config);
        }
    }

    private function registerModule($name, $config): void
    {
        if (!isset($config['path'])) {
            throw new LogicException('Module [' . $name . '] config param [path] required');
            //$config['path'] = app_path($name);
        }

        if (!isset($config['namespace'])) {
            $ns = str_replace($this->moduleManager->modulesPath() . '/', '', $config['path']);
            $ns = str_replace('/', '\\', $ns);
            $config['namespace'] = $this->moduleManager->modulesNamespace($ns);
        }

        $bootServiceProvider = $config['namespace'] . '\\Providers\\BootServiceProvider';
        if (!class_exists($bootServiceProvider)) {
            throw new LogicException('Module boot provider [' . $bootServiceProvider . '] not implemented');
        }
        $module = new Module(
            $name,
            $config,
            $this->sharedContainer
        );

        $module->register($bootServiceProvider);

        $this->moduleManager->modules()->add($module);
    }
}
