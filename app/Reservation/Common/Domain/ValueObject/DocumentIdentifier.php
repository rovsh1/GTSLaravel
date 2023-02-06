<?php

namespace GTS\Reservation\Common\Domain\ValueObject;

class DocumentIdentifier
{
    public function __construct(
        private readonly int $id,
        private readonly DocumentTypeEnum $type,
        private readonly int $version = 0,
        //private readonly int $reservationId,
    ) {}
}
