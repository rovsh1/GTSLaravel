<?php

namespace GTS\Shared\Application\Dto;

class Optional extends \Spatie\LaravelData\Optional
{
    public static function create(): self
    {
        return new self();
    }
}
