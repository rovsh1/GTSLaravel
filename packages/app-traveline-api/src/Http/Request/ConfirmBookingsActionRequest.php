<?php

namespace Pkg\App\Traveline\Http\Request;

use Pkg\Supplier\Traveline\Dto\Request\Reservation;

class ConfirmBookingsActionRequest extends AbstractTravelineRequest
{
    public function rules()
    {
        return array_merge([
            'data.confirmBookings' => 'required|array',
            'data.confirmBookings.*.number' => 'required|numeric',
            'data.confirmBookings.*.externalNumber' => 'required',
            'data.confirmBookings.*.status' => 'required|string',
        ], parent::rules());
    }

    /**
     * @return Reservation[]
     */
    public function getReservations(): array
    {
        $data = $this->getData()['confirmBookings'] ?? [];
        if (!empty($data)) {
            return Reservation::collectionFromArray($data);
        }

        return [];
    }
}
