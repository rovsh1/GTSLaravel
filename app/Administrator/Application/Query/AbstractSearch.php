<?php

namespace GTS\Administrator\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class AbstractSearch implements QueryInterface
{
    public function __construct(
        public readonly array $filters,
        public readonly ?string $orderBy,
        public readonly ?string $sortOrder,
        public readonly ?int $limit,
        public readonly ?int $offset
    ) {}

    public function __get(string $name)
    {
        return $this->filters[$name] ?? null;
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
