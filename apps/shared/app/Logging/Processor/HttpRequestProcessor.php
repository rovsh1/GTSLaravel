<?php

declare(strict_types=1);

namespace App\Shared\Logging\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class HttpRequestProcessor implements ProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        if (app()->runningInConsole()) {
            return $record;
        }

        $record->extra['http'] = $this->getValues();

        return $record;
    }

    private function getValues(): array
    {
        $request = request();

        return [
            'http' => array_filter([
                'host' => $request->getHttpHost(),
                'method' => $request->getMethod(),
                'uri' => $request->getUri(),
                'referer' => $request->headers->get('referer'),
                'userIp' => $request->ip(),
                'userAgent' => $request->userAgent(),
            ])
        ];
    }
}
