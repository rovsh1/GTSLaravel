<?php

declare(strict_types=1);

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\PortGateway\Request;
use Illuminate\Validation\Rules\Enum;
use Module\Hotel\Application\Command\UpdateMarkupSettingsValue;
use Module\Hotel\Application\Enums\UpdateMarkupSettingsActionEnum;
use Module\Hotel\Application\Query\GetHotelMarkupSettings;

class MarkupSettingsController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function getHotelMarkupSettings(Request $request): mixed
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
        ]);

        return $this->queryBus->execute(new GetHotelMarkupSettings($request->hotel_id));
    }

    public function updateMarkupSettingsValue(Request $request): void
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
}
