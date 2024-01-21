<?php

namespace App\Shared\Providers;

use App\Shared\Logging\Processor\ContextProcessor;
use App\Shared\Logging\Processor\HttpRequestProcessor;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use Monolog\Processor\MemoryUsageProcessor;
use Sdk\Shared\Contracts\Context\ContextInterface;

class LogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->callAfterResolving('log', function (LogManager $logManager) {
            $this->bootLogstashLogger($logManager->channel('logstash'));
        });
    }

    private function bootLogstashLogger($logger): void
    {
        $logger->pushProcessor(new HttpRequestProcessor());
        $logger->pushProcessor(new ContextProcessor($this->app));
        $logger->pushProcessor(new MemoryUsageProcessor());
    }
}
