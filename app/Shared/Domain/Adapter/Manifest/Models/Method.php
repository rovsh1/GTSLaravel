<?php

namespace GTS\Shared\Domain\Adapter\Manifest\Models;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class Method extends Data
{
    public function __construct(
        public readonly string         $name,
        /** @var Argument[] $arguments */
        #[DataCollectionOf(Argument::class)]
        public readonly DataCollection $arguments
    ) {}
}
