<?php

namespace Custom\Framework\Foundation\Support\Dto;

class Optional extends \Spatie\LaravelData\Optional
{
    public static function create(): self
    {
        return new self();
    }
}
