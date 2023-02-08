<?php

namespace Custom\Framework\Foundation\Support\Dto;

use Attribute;
use Spatie\LaravelData\Attributes\DataCollectionOf;

#[Attribute(Attribute::TARGET_PROPERTY)]
class DtoCollectionOf extends DataCollectionOf
{

}
