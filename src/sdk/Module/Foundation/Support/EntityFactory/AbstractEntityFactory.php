<?php

namespace Sdk\Module\Foundation\Support\EntityFactory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * @template T
 */
abstract class AbstractEntityFactory implements FactoryInterface
{
    /**
     * @var class-string<T> $entity
     */
    protected string $entity;

    public function __construct() {}

    /**
     * @param mixed $data
     * @return T
     * @throws \ReflectionException
     */
    public function createFrom(mixed $data)
    {
        $entityData = $data;
        if ($data instanceof Arrayable) {
            $entityData = $data->toArray();
        }
        return $this->fromArray($entityData);
    }

    /**
     * @param Collection|array $items
     * @return array<int, T>
     * @throws \ReflectionException
     */
    public function createCollectionFrom(Collection|array $items): array
    {
        $preparedItems = $items;
        if ($items instanceof Collection) {
            $preparedItems = $items->all();
        }
        return array_map(fn($itemData) => $this->createFrom($itemData), $preparedItems);
    }

    /**
     * @param array $data
     * @return T
     * @throws \ReflectionException
     */
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
