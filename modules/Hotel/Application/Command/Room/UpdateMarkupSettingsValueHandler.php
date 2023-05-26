<?php

namespace Module\Hotel\Application\Command\Room;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;
use Module\Shared\Domain\ValueObject\Percent;

class UpdateMarkupSettingsValueHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly RoomMarkupSettingsRepositoryInterface $repository
    ) {}

    public function handle(CommandInterface|UpdateMarkupSettingsValue $command): void
    {
        $settings = $this->repository->get($command->id);

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
