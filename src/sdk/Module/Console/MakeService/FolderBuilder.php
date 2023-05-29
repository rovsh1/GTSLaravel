<?php

namespace Sdk\Module\Console\MakeService;

class FolderBuilder
{
    private const FOLDERS = [
        'Application/Command',
        'Application/Event',
        'Application/Query',
        'Application/Dto',
        'Domain/Entity',
        'Domain/Exception',
        'Domain/Event',
        'Domain/Repository',
        'Domain/ValueObject',
        'Domain/Service',
        'Infrastructure/Adapter',
        'Infrastructure/Model',
        'Infrastructure/Query',
        'Infrastructure/Repository',
        'Port/Controllers',
        'Providers',
    ];

    private readonly string $moduleName;

    private string $modulePath;

    public function __construct(string $moduleName)
    {
        $this->moduleName = ucfirst($moduleName);
        $this->modulePath = modules_path($this->moduleName);
    }

    public function build(): void
    {
        if (is_dir($this->modulePath)) {
            throw new \Exception('Module [' . $this->moduleName . '] already exists');
        }

        self::makeDir($this->modulePath);
        foreach (self::FOLDERS as $folder) {
            self::makeDir($this->modulePath . DIRECTORY_SEPARATOR . $folder);
        }

        //$this->addExamples();

        $this->addGitKeepFiles();
    }

    private function addExamples()
    {
        $builder = new StubBuilder($this->moduleName);
        $builder->namespace('Application/Command')
            ->build('command', 'ExampleCommand')
            ->build('command-handler', 'ExampleCommandHandler');

        $builder->namespace('Application/Query')
            ->build('command', 'ExampleQuery')
            ->namespace('Infrastructure/Query')
            ->build('command-handler', 'ExampleQueryHandler');
    }

    private function addGitKeepFiles(string $subPath = ''): void
    {
        $scanPath = $this->modulePath . $subPath;
        $handle = opendir($scanPath);
        if (!$handle) {
            return;
        }

        $count = 0;
        while (false !== ($entry = readdir($handle))) {
            if (in_array($entry, ['.', '..'])) {
                continue;
            }

            $count++;
            $sub = $subPath . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($this->modulePath . $sub)) {
                $this->addGitKeepFiles($sub);
            }
        }

        if ($count === 0) {
            $this->makeGitKeepFile($scanPath);
        }

        closedir($handle);
    }

    private function makeGitKeepFile($path): void
    {
        $filename = $path . DIRECTORY_SEPARATOR . '.gitkeep';
        $h = fopen($filename, 'w');
        fclose($h);
    }

    private static function makeDir($path): void
    {
        mkdir($path, 0755, true);
    }
}