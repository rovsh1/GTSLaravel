<?php

declare(strict_types=1);

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\PortGateway\Request;
use Illuminate\Validation\Rules\Enum;
use Module\Hotel\Application\Command\Room;
use Module\Hotel\Application\Command\UpdateMarkupSettingsValue;
use Module\Hotel\Application\Enums\UpdateMarkupSettingsActionEnum;
use Module\Hotel\Application\Query\GetHotelMarkupSettings;
use Module\Hotel\Application\Query\GetRoomMarkupSettings;

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

        return $this->queryBus->execute(new GetRoomMarkupSettings($request->room_id));
    }

    public function updateRoomMarkupSettings(Request $request): void
    {
        $request->validate([
            'room_id' => ['required', 'numeric'],
            'key' => ['required', 'string'],
            'value' => ['required', 'numeric'],
        ]);

        $this->commandBus->execute(
            new Room\UpdateMarkupSettingsValue($request->room_id, $request->key, $request->value)
        );
    }
}
