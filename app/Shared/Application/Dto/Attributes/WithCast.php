<?php

namespace GTS\Shared\Application\Dto\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WithCast extends \Spatie\LaravelData\Attributes\WithCast
{

}
