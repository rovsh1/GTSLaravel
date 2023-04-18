<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Hotel\Domain\Entity\Reservation;

class VoucherGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'voucher.html';
    }

    protected function getReservationAttributes(Reservation $reservation): array
    {
//        $this->hotel = $reservation->getHotel();
//        $requisites = $this->getRequisites($reservation);
//        $requisites['created'] = (new DateTime('now'))->format('d.m.Y H:i');
//        $requisites['hotelAddress'] = $this->hotel->address;
//        $requisites['reservStatus'] = \RESERVATION_STATUS::getLabel($reservation->status);
//        $requisites['rooms'] = $this->getRoomsHtml($reservation, \RESERVATION_REQUEST::VOUCHER);
//        $request = App::factory('Reservation\Request');
//        $id = $request->findLastId();
//        $requisites['number'] = \format\no($id);
//        self::setCancelPeriods($reservation, $requisites);
//        $fileName = 'voucher_' . $id . '.pdf';
        return [];
    }
}
