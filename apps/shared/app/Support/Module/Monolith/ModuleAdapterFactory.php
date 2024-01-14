<?php

namespace App\Shared\Support\Module\Monolith;

use LogicException;
use Sdk\Module\Foundation\Module;
use Sdk\Module\Foundation\Support\SharedContainer;

class ModuleAdapterFactory
{
    public function __construct(
        private readonly SharedContainer $sharedContainer,
    ) {}

    public function build(string $name, string $path, string $namespace, array $config): ModuleAdapter
    {
//        $path = $this->modulesPath . DIRECTORY_SEPARATOR . $relativePath;
//        $namespace = $this->modulesNamespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

//        $configPath = $path . DIRECTORY_SEPARATOR . 'config.php';
//        if (!file_exists($configPath)) {
//            throw new LogicException("Module[$name] config required");
//        }
//        $config = include $configPath;
        $config['path'] = $path;
        $config['namespace'] = $namespace;

        $module = new Module($name, $config, $this->sharedContainer);

        $bootServiceProvider = $namespace . '\\Providers\\BootServiceProvider';
        if (class_exists($bootServiceProvider)) {
            $module->register($bootServiceProvider);
//            throw new LogicException('Module boot provider [' . $bootServiceProvider . '] not implemented');
        }
        $module->register(CommonServiceProvider::class);

        return new ModuleAdapter($name, $module);
    }
}
