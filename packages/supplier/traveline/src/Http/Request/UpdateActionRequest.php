<?php

namespace Pkg\Supplier\Traveline\Http\Request;

use Pkg\Supplier\Traveline\Dto\Request\Update;

class UpdateActionRequest extends AbstractTravelineRequest
{
    public function rules()
    {
        return array_merge([
            'data.hotelId' => 'required|numeric',
            'data.updates' => 'required|array'
        ], parent::rules());
    }

    public function getHotelId(): int
    {
        return $this->getData()['hotelId'];
    }

    public function getUpdates(): array
    {
        $updates = $this->getData()['updates'] ?? [];
        if (empty($updates)) {
            return [];
        }

        return Update::collectionFromArray($updates);
    }
}
