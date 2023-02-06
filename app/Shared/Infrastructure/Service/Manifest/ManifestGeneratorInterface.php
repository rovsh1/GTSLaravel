<?php

namespace GTS\Shared\Infrastructure\Service\Manifest;

interface ManifestGeneratorInterface
{
    public function generateModuleManifest(string $moduleName): void;
}
