<?php

namespace Sdk\Module\Services;

use BackedEnum;
use LogicException;
use ReflectionClass;
use ReflectionProperty;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class IntegrationEventSerializer
{
    public function serialize(IntegrationEventInterface $event): array
    {
        $payload = [];
        $reflection = new ReflectionClass($event);
        foreach ($reflection->getProperties() as $property) {
            $payload[$property->name] = $this->castProperty($event->{$property->name});
        }

        return $payload;
    }

    public function deserialize(string $eventClass, array $payload): IntegrationEventInterface
    {
        $attributes = [];
        $reflection = new ReflectionClass($eventClass);
        foreach ($reflection->getProperties() as $property) {
            $attributes[$property->name] = $this->buildProperty($property, $payload[$property->name]);
        }

        return new $eventClass(...$attributes);
    }

    private function castProperty(mixed $value): mixed
    {
        if (is_null($value)) {
            return null;
        } elseif (is_scalar($value)) {
            return $value;
        } elseif ($value instanceof BackedEnum) {
            return $value->value;
//        } elseif (is_array($value)) {
//            $tmp = [];
//            foreach ($value as $k => $v) {
//                $tmp[$k] = $this->castProperty($v);
//            }
//
//            return $tmp;
        } else {
            dd($value);
            throw new LogicException();
        }
    }

    private function buildProperty(ReflectionProperty $property, mixed $value): mixed
    {
        if ($property->getType()->isBuiltin()) {
            return $value;
//            if ($property->getType()->getName() === 'array') {
//                $tmp = array
//            } else {
//            }
        }

        $typeClass = $property->getType()->getName();
        if (is_subclass_of($typeClass, BackedEnum::class)) {
            return $typeClass::from($value);
        } else {
            dd($value);
            throw new LogicException();
        }
    }
}