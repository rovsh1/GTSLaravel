<?php

namespace GTS\Administrator\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class AbstractCount implements QueryInterface
{
    public function __construct(
        public readonly array $filters
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
