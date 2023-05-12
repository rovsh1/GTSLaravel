<?php

namespace Module\Hotel\Application\Command\Room;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;

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

        $paramName = $command->key;
        $settings->$paramName()->setValue($command->value);

        $this->repository->update($settings);
    }
}
