<?php

namespace GTS\Shared\Application\Dto\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class MapInputName extends \Spatie\LaravelData\Attributes\MapInputName
{

}
