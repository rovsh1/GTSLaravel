<?php

namespace Custom\Framework\Foundation\Support\EntityFactory;

use Attribute;
use Spatie\LaravelData\Attributes\DataCollectionOf;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EntityCollectionOf extends DataCollectionOf
{

}
