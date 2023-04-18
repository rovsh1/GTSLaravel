<?php

namespace Module\Booking\Hotel\Domain\Entity;

use Module\Shared\Domain\Entity\FileInterface;
use Module\Booking\Common\Domain\Entity\AbstractDocument;
use Module\Booking\Common\Domain\ValueObject\DocumentTypeEnum;

class CancellationRequest extends AbstractDocument implements FileInterface
{
    public function type(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CANCELLATION;
    }
}
