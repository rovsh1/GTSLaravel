<?php

namespace GTS\Administrator\Application\Query;

use GTS\Shared\Application\Query\QueryInterface;

abstract class AbstractSearchQuery implements QueryInterface
{
    public function __construct(
        private readonly array $filters,
        private readonly ?string $orderBy,
        private readonly ?string $sortOrder,
        private readonly ?int $limit,
        private readonly ?int $offset
    ) {
    }

    public function __get(string $name)
    {
        return $this->filters[$name] ?? null;
    }

    public function filters(): array
    {
        return $this->filters;
    }

    public function orderBy(): ?string
    {
        return $this->orderBy;
    }

    public function sortOrder(): ?string
    {
        return $this->sortOrder;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }

    public static function fromDto($dto): static
    {
        static $properties = ['orderBy', 'sortOrder', 'limit', 'offset'];
        $arguments = [[], null, null, null, null];
        foreach ($dto as $key => $value) {
            $i = array_search($key, $properties);
            if (false === $i)
                $arguments[0][$key] = $value;
            else
                $arguments[$i + 1] = $value;
        }
        return new static(...$arguments);
        /* $arguments = [];
         $reflection = new \ReflectionClass(static::class);
         $constructorArgs = $reflection->getConstructor()->getParameters();
         foreach ($constructorArgs as $parameter) {
         }
         dd($constructorArgs);
         foreach ($reflection->getProperties() as $property) {
             if (isset($dto->{$property->getName()})) {
                 $property->setValue();
             } elseif (!$property->hasDefaultValue())
                 throw new \Exception('Property requuired');
         }

         return new static();*/
    }
}
