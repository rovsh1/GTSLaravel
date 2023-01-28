<?php

namespace GTS\Administrator\Application\Query;

use GTS\Shared\Application\Query\QueryInterface;

class AbstractCount implements QueryInterface
{
    public function __construct(
        private readonly array $filters
    ) {}

    public function __get(string $name)
    {
        return $this->filters[$name] ?? null;
    }

    public static function fromDto($dto): static
    {
        $filters = [];
        foreach ($dto as $key => $value) {
            $filters[$key] = $value;
        }

        return new static($filters);
    }
}
