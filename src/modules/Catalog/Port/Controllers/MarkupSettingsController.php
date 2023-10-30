<?php

declare(strict_types=1);

namespace Module\Catalog\Port\Controllers;

use Illuminate\Validation\Rules\Enum;
use Module\Catalog\Application\Admin\Command\UpdateMarkupSettingsValue;
use Module\Catalog\Application\Admin\Enums\UpdateMarkupSettingsActionEnum;
use Module\Catalog\Application\Admin\Query\GetHotelMarkupSettings;
use Module\Catalog\Application\Admin\Query\GetRoomMarkups;
use Module\Hotel\Application\Command\Room;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\PortGateway\Request;

class MarkupSettingsController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function getHotelMarkupSettings(Request $request): mixed
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
        ]);

        return $this->queryBus->execute(new GetHotelMarkupSettings($request->hotel_id));
    }

    public function updateHotelMarkupSettingsValue(Request $request): void
    {
        $request->validate([
            'hotel_id' => ['required', 'numeric'],
            'key' => ['required', 'string'],
            'value' => 'required',
            'action' => ['required', new Enum(UpdateMarkupSettingsActionEnum::class)]
        ]);

        $this->commandBus->execute(
            new UpdateMarkupSettingsValue(
                $request->hotel_id,
                $request->key,
                $request->value,
                UpdateMarkupSettingsActionEnum::from($request->action)
            )
        );
    }

    public function getRoomMarkupSettings(Request $request): mixed
    {
        $request->validate([
            'room_id' => ['required', 'numeric'],
        ]);

        return $this->queryBus->execute(new GetRoomMarkups($request->room_id));
    }

    public function updateRoomMarkupSettings(Request $request): void
    {
        $request->validate([
            'room_id' => ['required', 'numeric'],
            'key' => ['required', 'string'],
            'value' => ['required', 'numeric'],
        ]);

        $this->commandBus->execute(
            new \Module\Catalog\Application\Admin\Command\Room\UpdateMarkupSettingsValue($request->room_id, $request->key, $request->value)
        );
    }
}
