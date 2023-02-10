<?php

namespace Custom\Framework\Foundation\Support\EntityFactory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractEntityFactory implements FactoryInterface
{
    protected string $entity;

    public function __construct() {}

    public function createFrom(mixed $data)
    {
        $entityData = $this->toArray($data);
        return $this->createEntity($entityData);
    }

    public function createCollectionFrom(Collection|array $items): array
    {
        $preparedItems = $items;
        if ($items instanceof Collection) {
            $preparedItems = $items->all();
        }
        return array_map(fn($itemData) => $this->createFrom($itemData), $preparedItems);
    }

    public function toArray(mixed $data): array
    {
        $preparedData = $data;
        if ($data instanceof Arrayable) {
            $preparedData = $data->toArray();
        }
        return $preparedData;
    }

    protected function createEntity(array $data)
    {
        if ($this->entity === null) {
            throw new \Exception('Entity not defined');
        }
        $entityClass = new \ReflectionClass($this->entity);
        if ($entityClass->getConstructor() === null) {
            throw new \Exception('Entity not instantiable');
        }
        $arguments = $entityClass->getConstructor()->getParameters();
        $preparedArguments = [];
        foreach ($arguments as $argument) {
            $argumentName = $argument->getName();
            $preparedArguments[$argumentName] = $data[$argumentName] ?? null;
        }
        return new $this->entity(...$preparedArguments);
    }
}
