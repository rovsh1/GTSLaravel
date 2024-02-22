<?php

namespace Module\Shared\Support\Testing;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Sdk\Module\Contracts\ModuleInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ModuleInterface $module;

    protected function setUp(): void
    {
        parent::setUp();

        if (!isset($this->module)) {
//            $this->module = app(ModulesManager::class)->findByNameSpace(get_class($this));
            $this->module->boot();
        }
    }
}
