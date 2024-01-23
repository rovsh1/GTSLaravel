<?php

declare(strict_types=1);

namespace App\Shared\Logging;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\LogRecord;
use Monolog\Utils;

class LogstashFormatter extends NormalizerFormatter
{
    protected string $extraKey;

    protected string $contextKey;

    public function __construct(
        string $extraKey = 'extra',
        string $contextKey = 'context'
    ) {
        // logstash requires a ISO 8601 format date with optional millisecond precision.
        parent::__construct('Y-m-d\TH:i:s.uP');

        $this->extraKey = $extraKey;
        $this->contextKey = $contextKey;
    }

    /**
     * @inheritDoc
     */
    public function format(LogRecord $record): string
    {
        $recordData = parent::format($record);

        $message = [
            '@timestamp' => $recordData['datetime'],
            ...$this->getMainFields($recordData)
        ];

        $context = $recordData['context'];
        $extra = $recordData['extra'];

        $this->mergeFromSource($message, $extra, 'flow');
        $this->moveFromSource($message, $extra, 'http');
        $this->moveFromSource($message, $context, 'exception');

        if (\count($extra) > 0) {
            $message[$this->extraKey] = $extra;
        }
        if (\count($context) > 0) {
            $message[$this->contextKey] = $context;
        }

        return $this->toJson($message) . "\n";
    }

    private function moveFromSource(array &$message, array &$source, string $key): void
    {
        if (!isset($source[$key])) {
            return;
        }

        $message[$key] = $source[$key];
        unset($source[$key]);
    }

    private function mergeFromSource(array &$message, array &$source, string $key): void
    {
        if (isset($source[$key])) {
            $message = array_merge($message, $source[$key]);
            unset($source[$key]);
        }
    }

    private function getMainFields(array $recordData): array
    {
        static $copyFields = [
            'message',
            'channel',
            'level',
            'level_name',
        ];
        $message = [];
        foreach ($copyFields as $key) {
            if (isset($recordData[$key])) {
                $message[$key] = $recordData[$key];
            }
        }

        return $message;
    }

    protected function normalizeException(\Throwable $e, int $depth = 0): array
    {
        if ($e instanceof \JsonSerializable) {
            return (array)$e->jsonSerialize();
        }

        $trace = $e->getTraceAsString();
        if (($previous = $e->getPrevious()) instanceof \Throwable) {
            $trace .= $this->formatPreviousException($previous);
        }

        return [
            'class' => Utils::getClass($e),
            'message' => $e->getMessage(),
            'code' => (int)$e->getCode(),
            'file' => $e->getFile() . ':' . $e->getLine(),
            'trace' => $trace
        ];
    }

    private function formatPreviousException(\Throwable $e): string
    {
        $str = "\n\n" . '[previous exception] '
            . $e::class . "(code: {$e->getCode()}): "
            . ($e->getMessage() ?: '<empty>')
            . " at {$e->getFile()})";

        $str .= "\n" . '[stacktrace]';
        $str .= "\n" . $e->getTraceAsString();

        if (($previous = $e->getPrevious()) instanceof \Throwable) {
            $str .= $this->formatPreviousException($previous);
        }

        return $str;
    }
}
