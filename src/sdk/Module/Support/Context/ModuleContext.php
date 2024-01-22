<?php

namespace Sdk\Module\Support\Context;

use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Support\ApplicationContext\AbstractContext;

class ModuleContext extends AbstractContext implements ContextInterface
{
    public function __construct(
        private readonly ModuleInterface $module
    ) {}

    public function module(): ModuleInterface
    {
        return $this->module;
    }

    public function setPrevContext(array $data): void
    {
        static $fields = [
            'requestId',
        ];
        foreach ($fields as $key) {
            if (isset($data[$key])) {
                $this->data[$key] = $data[$key];
            }
        }
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'module' => $this->module->name()
        ]);
    }
}