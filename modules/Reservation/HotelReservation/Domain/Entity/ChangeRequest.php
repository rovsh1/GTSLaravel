<?php

namespace Module\Reservation\HotelReservation\Domain\Entity;

use Module\Shared\Domain\Entity\FileInterface;
use Module\Reservation\Common\Domain\Entity\AbstractDocument;
use Module\Reservation\Common\Domain\ValueObject\DocumentTypeEnum;

class ChangeRequest extends AbstractDocument implements FileInterface
{
    public function type(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CHANGE;
    }
}
