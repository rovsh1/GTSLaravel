<?php

namespace Custom\Framework\Console\MakeService;

class StubBuilder
{
    private readonly string $moduleName;

    private string $modulePath;

    private string $path;

    private string $namespace;

    public function __construct(string $moduleName)
    {
        $this->moduleName = ucfirst($moduleName);
        $this->modulePath = modules_path($this->moduleName);
    }

    public function build(string $stubName, string $className): static
    {
        $stub = __DIR__ . '/stubs/' . $stubName . '.stub';
        $content = file_get_contents($stub);
        $content = str_replace(['{{ Namespace }}', '{{ namespace }}'], $this->namespace, $content);
        $content = str_replace(['{{ Class }}', '{{ class }}'], $className, $content);

        $filename = $this->path . DIRECTORY_SEPARATOR . $className . '.php';
        $h = fopen($filename, 'w');
        fwrite($h, $content);
        fclose($h);

        return $this;
    }

    public function namespace(string $path): static
    {
        $this->path = $this->modulePath . DIRECTORY_SEPARATOR . $path;
        $this->namespace = 'Module\\' . $this->moduleName . '\\' . str_replace('/', '\\', $path);
        return $this;
    }
}