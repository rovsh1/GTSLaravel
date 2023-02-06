<?php

namespace GTS\Reservation\HotelReservation\Domain\Entity;

use GTS\Reservation\Common\Domain\Entity\AbstractDocument;
use GTS\Reservation\Common\Domain\ValueObject\DocumentTypeEnum;
use GTS\Shared\Domain\Entity\FileInterface;

class Voucher extends AbstractDocument implements FileInterface
{
    public function type(): DocumentTypeEnum
    {
        return DocumentTypeEnum::VOUCHER;
    }

    public function guid(): string
    {
        return $this->file()->guid();
    }
}
