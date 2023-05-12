<?php

namespace Module\Hotel\Application\Command\Room;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class UpdateMarkupSettingsValueHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly RoomMarkupSettingsRepositoryInterface $repository
    ) {}

    public function handle(CommandInterface|UpdateMarkupSettingsValue $command): void
    {
        $settings = $this->repository->get($command->id);



        $this->repository->update($settings);
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
