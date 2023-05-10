<?php

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Illuminate\Support\Collection;
use Module\Hotel\Application\Enums\UpdateMarkupSettingsActionEnum;
use Module\Hotel\Domain\Repository\MarkupSettingsRepositoryInterface;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class UpdateMarkupSettingsValueHandler implements CommandHandlerInterface
{
    private array $dtoKeysMap = [
        'earlyCheckIn.*.from' => 'earlyCheckIn.*.timePeriod',
        'earlyCheckIn.*.to' => 'earlyCheckIn.*.timePeriod',
        'earlyCheckIn.*.percent' => 'earlyCheckIn.*.priceMarkup',
        //@todo сделать свой Period на базе Carbon у которого будут методы setFrom и setTo
        'cancelPeriods.*.from' => 'cancelPeriods.*.period',
        'cancelPeriods.*.to' => 'cancelPeriods.*.period',
    ];

    public function __construct(
        private readonly MarkupSettingsRepositoryInterface $repository
    ) {}

    public function handle(CommandInterface|UpdateMarkupSettingsValue $command): void
    {
        $settings = $this->repository->get($command->id);
        [$domainKey, $objectKey] = $this->getDomainKey($command->key);
        $object = $this->getObjectByKey($settings, $domainKey);
        if ($command->action === UpdateMarkupSettingsActionEnum::UPDATE) {
            $this->setByObjectKey($object, $objectKey, $command->value);
        } elseif ($command->action === UpdateMarkupSettingsActionEnum::ADD_TO_COLLECTION) {
            //@todo 1. Получить тип экземпляра коллекции
            //@todo 2. преобразование array в доменный объект + проверка на валидность
            dd($object);
            $object->add($command->value);
        } elseif ($command->action === UpdateMarkupSettingsActionEnum::DELETE_FROM_COLLECTION) {
            $object->offsetUnset($command->value);
        }
        $this->repository->update($settings);
    }

    /**
     * @param string $dtoKey
     * @return string[]
     */
    private function getDomainKey(string $dtoKey): array
    {
        $domainKey = $dtoKey;
        $objectKey = \Str::afterLast($dtoKey, '.');

        $keyForMatching = preg_replace('/\d+/m', '*', $dtoKey);
        if (array_key_exists($keyForMatching, $this->dtoKeysMap)) {
            preg_match('/\d+/m', $dtoKey, $matches);
            $replacements = array_fill(0, count($matches), '*');
            $domainKey = str_replace($replacements, $matches, $this->dtoKeysMap[$keyForMatching]);
        }

        return [$domainKey, $objectKey];
    }

    private function getObjectByKey(
        ValueObjectInterface|EntityInterface $parent,
        string $key
    ): ValueObjectInterface|Collection {
        $keyParts = explode('.', $key);
        $childKey = array_shift($keyParts);

        if (method_exists($parent, $childKey)) {
            $childValue = $parent->$childKey();
        } elseif (is_numeric($childKey) && $parent instanceof Collection) {
            $childValue = $parent->get($childKey);
        } elseif (array_key_exists($childKey, $this->dtoKeysMap)) {
//            dd($childKey);
        } else {
            throw new \InvalidArgumentException("Unknown key [$childKey]");
        }

        if (count($keyParts) > 0) {
            return $this->getObjectByKey($childValue, implode('.', $keyParts));
        }

        if (!is_object($childValue)) {
            $childValue = $parent;
        }

        return $childValue;
    }

    private function setByObjectKey(ValueObjectInterface|EntityInterface $object, string $key, mixed $value): void
    {
        $setterMethod = 'set' . \Str::ucfirst($key);
        if (method_exists($object, $setterMethod)) {
            $object->$setterMethod($value);
        } elseif (method_exists($object, 'setValue')) {
            $object->setValue($value);
        } else {
            throw new \InvalidArgumentException("Can not update value for key [$key]");
        }
    }
}
