<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator;

use GTS\Reservation\Domain\Entity\Document\Voucher;
use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;

class VoucherGenerator extends AbstractGenerator
{
    public function generate(ReservationRequestableInterface $document): Voucher {}
}
