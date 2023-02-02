<?php

namespace GTS\Shared\Domain\Factory;

use Attribute;
use Spatie\LaravelData\Attributes\DataCollectionOf;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EntityCollectionOf extends DataCollectionOf
{

}
