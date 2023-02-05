<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator\HotelReservation;

use GTS\Reservation\Domain\Entity\Document\Voucher;
use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;
use GTS\Reservation\Domain\Service\DocumentGenerator\AbstractGenerator;

class VoucherGenerator extends AbstractGenerator
{
    public function generate(ReservationRequestableInterface $document): Voucher {}

    public function getDocumentPath(): string
    {
        return $this->templatesPath . DIRECTORY_SEPARATOR . 'hotel_voucher.html';
    }
}
