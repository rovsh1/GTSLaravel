<?php

namespace Sdk\Module\Foundation\Support\Dto\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WithTransformer extends \Spatie\LaravelData\Attributes\WithTransformer
{

}