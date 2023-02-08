<?php

namespace Custom\EntityFactory;

use Attribute;
use Spatie\LaravelData\Attributes\DataCollectionOf;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EntityCollectionOf extends DataCollectionOf
{

}
