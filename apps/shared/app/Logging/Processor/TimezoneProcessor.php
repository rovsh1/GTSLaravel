<?php

declare(strict_types=1);

namespace App\Shared\Logging\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class TimezoneProcessor implements ProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['timezone'] = date_default_timezone_get();

        return $record;
    }
}
