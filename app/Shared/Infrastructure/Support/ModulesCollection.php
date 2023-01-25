<?php

namespace GTS\Shared\Infrastructure\Support;

class ModulesCollection {

    public function __construct(
        private readonly array $modules
    ) {
    }

    public function names(): array {
        return $this->modules;
    }

    public function paths(): array {
        return array_map(fn(string $moduleName) => $this->convertModuleNameToPath($moduleName), $this->modules);
    }

    private function convertModuleNameToPath(string $moduleName): string {
        return app_path(\Str::of($moduleName)->replace('\\', '/'));
    }
}
