<?php

namespace Custom\Framework\Port;

use Custom\Framework\Foundation\Exception\ValidationException;
use Illuminate\Support\Facades\Validator;

class Request
{
    public function __construct(
        private readonly string $path,
        private readonly array $attributes
    ) {}

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function validate(array $rules): array
    {
        $validator = Validator::make($this->attributes, $rules);
        if ($validator->fails()) {
            throw new ValidationException('Validation failed: ' . $validator->errors());
        }

        return $validator->validated();
    }
}
