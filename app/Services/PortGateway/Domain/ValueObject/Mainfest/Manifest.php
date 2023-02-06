<?php

namespace GTS\Services\PortGateway\Domain\ValueObject\Mainfest;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class Manifest extends Data
{
    public function __construct(
        public readonly string $moduleName,
        /** @var Port[] $ports */
        #[DataCollectionOf(Port::class)]
        public readonly DataCollection  $ports
    ) {}
}
