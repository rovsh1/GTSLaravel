<?php

namespace Module\Client\Application\Dto;

final class AddBalanceDto
{
    public function __construct(
        public readonly int $clientId,
        public readonly float $sum,
        public readonly array $context = [],
    ) {
    }
}