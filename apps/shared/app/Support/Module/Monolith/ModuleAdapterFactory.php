<?php

namespace App\Shared\Support\Module\Monolith;

use LogicException;
use Sdk\Module\Foundation\Module;
use Sdk\Module\Foundation\Support\SharedContainer;

class ModuleAdapterFactory
{
    public function __construct(
        private readonly string $modulesPath,
        private readonly string $modulesNamespace,
        private readonly SharedContainer $sharedContainer,
    ) {
    }

    public function build(string $name, array $config): ModuleAdapter
    {
        $relativePath = $config['path'];
        $namespace = $this->modulesNamespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

        $bootServiceProvider = $namespace . '\\Providers\\BootServiceProvider';
        if (!class_exists($bootServiceProvider)) {
            throw new LogicException('Module boot provider [' . $bootServiceProvider . '] not implemented');
        }

        $config['namespace'] = $namespace;

        $module = new Module($name, $config, $this->sharedContainer);
        $module->register($bootServiceProvider);

        return new ModuleAdapter($name, $module);
    }

//    public function build(string $name, string $relativePath): ModuleAdapter
//    {
//        $path = $this->modulesPath . DIRECTORY_SEPARATOR . $relativePath;
//        $namespace = $this->modulesNamespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);
//
//        $bootServiceProvider = $namespace . '\\Providers\\BootServiceProvider';
//        if (!class_exists($bootServiceProvider)) {
//            throw new LogicException('Module boot provider [' . $bootServiceProvider . '] not implemented');
//        }
//
//        $configPath = $path . DIRECTORY_SEPARATOR . 'config.php';
//        if (!file_exists($configPath)) {
//            throw new LogicException("Module[$name] config required");
//        }
//        $config = include $configPath;
//        $config['namespace'] = $namespace;
//
//        $module = new Module($name, $config, $this->sharedContainer);
//        $module->register($bootServiceProvider);
//
//        return new ModuleAdapter($name, $module);
//    }
}
