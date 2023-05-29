<?php

namespace Sdk\Module\Foundation\Support\EntityFactory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractEntityFactory implements FactoryInterface
{
    protected string $entity;

    public function __construct() {}

    public function createFrom(mixed $data)
    {
        $entityData = $data;
        if ($data instanceof Arrayable) {
            $entityData = $data->toArray();
        }
        return $this->fromArray($entityData);
    }

    public function createCollectionFrom(Collection|array $items): array
    {
        $preparedItems = $items;
        if ($items instanceof Collection) {
            $preparedItems = $items->all();
        }
        return array_map(fn($itemData) => $this->createFrom($itemData), $preparedItems);
    }

    protected function fromArray(array $data): mixed
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
