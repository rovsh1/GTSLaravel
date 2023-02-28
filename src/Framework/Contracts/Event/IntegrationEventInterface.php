<?php

namespace Custom\Framework\Contracts\Event;

interface IntegrationEventInterface
{
    public function module(): string;

    public function event(): string;

    public function key(): string;

    public function payload(): array;
}
