<?php

namespace GTS\Services\PortGateway\Domain\ValueObject\Mainfest;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class Port extends Data
{
    public function __construct(
        public readonly string         $name,
        /** @var Method[] $methods */
        #[DataCollectionOf(Method::class)]
        public readonly DataCollection $methods
    ) {}
}
