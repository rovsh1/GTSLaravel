<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\Booking;

class VoucherGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'hotel/voucher.html';
    }

    protected function getReservationAttributes(Booking $booking): array
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
