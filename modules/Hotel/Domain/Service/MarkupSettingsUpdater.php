<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Service;

use Illuminate\Support\Collection;
use Module\Hotel\Domain\Repository\MarkupSettingsRepositoryInterface;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class MarkupSettingsUpdater
{
    public function __construct(
        private readonly MarkupSettingsRepositoryInterface $repository,
    ) {}

    public function updateByKey(int $id, string $key, mixed $value): void
    {
        //AppService -> getObjectByKey (от доменной модели)

        //1. Получаю объект VO или Collection внутри Application по ключу (ссылка)
        //2. Изменяю его
        //3. Сохраняю все сеттинги
        $markupSettings = $this->repository->get($id);
        $this->set($markupSettings, $key, $value);
        $this->repository->update($markupSettings);
    }

    public function addCondition(int $id, string $key, mixed $value): void
    {
        $markupSettings = $this->repository->get($id);
        $collection = $this->getNestedCollection($markupSettings, $key);
        dd($collection);
//        $this->set($markupSettings, $key, $value);
        dd('add', $key, $value);
        $this->repository->update($markupSettings);
    }

    public function deleteCondition(int $id, string $key, int $index): void
    {
        $markupSettings = $this->repository->get($id);
        $collection = $this->getNestedCollection($markupSettings, $key);
        $collection->offsetUnset($index);
//        dd($collection,$markupSettings);
        //@todo как записать в Markup, как работать с сеттингами?
        dd('delete', $key, $index, $collection);
        $this->repository->update($markupSettings);
    }

    private function set(ValueObjectInterface|EntityInterface $parent, string $key, mixed $value): void
    {
        $keyParts = explode('.', $key);
        $childKey = array_shift($keyParts);

        if (method_exists($parent, $childKey)) {
            $childValue = $parent->$childKey();
        } elseif (is_numeric($childKey) && $parent instanceof Collection) {
            $childValue = $parent->get($childKey);
        } else {
            throw new \InvalidArgumentException("Unknown key [$childKey]");
        }

        if (count($keyParts) > 0) {
            $this->set($childValue, implode('.', $keyParts), $value);
            return;
        }

        if (!is_object($childValue)) {
            $childValue = $parent;
        }

        $setterMethod = 'set' . \Str::ucfirst($childKey);
        if (method_exists($childValue, $setterMethod)) {
            $childValue->$setterMethod($value);
        } elseif (method_exists($childValue, 'setValue')) {
            $childValue->setValue($value);
        } else {
            throw new \InvalidArgumentException("Can not update value for key [$childKey]");
        }
    }

    private function getNestedCollection(ValueObjectInterface|EntityInterface $parent, string $key): Collection
    {
        $keyParts = explode('.', $key);
        $childKey = array_shift($keyParts);

        if (method_exists($parent, $childKey)) {
            $childValue = $parent->$childKey();
        } elseif (is_numeric($childKey) && $parent instanceof Collection) {
            $childValue = $parent->get($childKey);
        } else {
            throw new \InvalidArgumentException("Unknown key [$childKey]");
        }

        if (count($keyParts) > 0) {
            return $this->getNestedCollection($childValue, implode('.', $keyParts));
        }

        if (!is_object($childValue)) {
            $childValue = $parent;
        }

        if (!$childValue instanceof Collection) {
            throw new \InvalidArgumentException("Value of key [$key] is not collection");
        }

        return $childValue;
    }
}
