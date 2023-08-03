<?php

namespace Module\Hotel\Application\Command\Room;

use Module\Hotel\Domain\Entity\Room\RoomMarkups;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;
use Module\Hotel\Domain\ValueObject\RoomId;
use Module\Shared\Domain\ValueObject\Percent;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class UpdateMarkupSettingsValueHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly RoomMarkupSettingsRepositoryInterface $repository
    ) {}

    public function handle(CommandInterface|UpdateMarkupSettingsValue $command): void
    {
        $settings = $this->repository->get($command->id);
        if ($settings === null) {
            $settings = RoomMarkups::buildEmpty(
                new RoomId($command->id)
            );
        }

        $reflection = new \ReflectionClass($settings);
        $settingsProps = collect($reflection->getProperties())->map->name->all();
        if (!in_array($command->key, $settingsProps)) {
            throw new \InvalidArgumentException("Unknown key [{$command->key}] for room markup settings");
        }

        $setterMethod = 'set' . \Str::ucfirst($command->key);
        if (method_exists($settings, $setterMethod)) {
            $settings->$setterMethod(new Percent($command->value));
        } else {
            throw new \InvalidArgumentException("Can not update value for key [{$command->key}]");
        }

        $this->repository->update($settings);
    }
}
