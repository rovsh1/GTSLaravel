<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

final class DescriptionDto
{
    public function __construct(
        public readonly string $text,
        public readonly ?string $color = null,
    ) {}
}