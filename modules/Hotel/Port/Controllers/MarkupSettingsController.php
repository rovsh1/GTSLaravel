<?php

declare(strict_types=1);

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\PortGateway\Request;
use Module\Hotel\Application\Query\GetHotelMarkupSettings;
use Module\Hotel\Domain\Service\MarkupSettingsUpdater;

class MarkupSettingsController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly MarkupSettingsUpdater $markupSettingsUpdater,
    ) {}

    public function getHotelMarkupSettings(Request $request): mixed
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
        ]);

        return $this->queryBus->execute(new GetHotelMarkupSettings($request->hotel_id));
    }

    public function updateMarkupSettings(Request $request): void
    {
        $request->validate([
            'hotel_id' => ['required', 'numeric'],
            'key' => ['required', 'string'],
            'value' => 'required'
        ]);

        $this->markupSettingsUpdater->updateByKey($request->hotel_id, $request->key, $request->value);
    }

    public function addMarkupSettingsCondition(Request $request): void
    {
        $request->validate([
            'hotel_id' => ['required', 'numeric'],
            'key' => ['required', 'string'],
            'value' => 'required'
        ]);

        $this->markupSettingsUpdater->addCondition($request->hotel_id, $request->key, $request->value);
    }

    public function deleteMarkupSettingsCondition(Request $request): void
    {
        $request->validate([
            'hotel_id' => ['required', 'numeric'],
            'key' => ['required', 'string'],
            'index' => ['required', 'integer']
        ]);
        $this->markupSettingsUpdater->deleteCondition($request->hotel_id, $request->key, $request->index);
    }
}
