<?php

namespace GTS\Shared\Application\Dto;

use Attribute;
use Spatie\LaravelData\Attributes\DataCollectionOf;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AbstractDtoCollectionOf extends DataCollectionOf
{

}
