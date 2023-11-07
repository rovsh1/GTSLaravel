<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Voucher;

use Module\Booking\Application\Admin\Shared\Support\UseCase\Request\GetDocumentFileInfo as Base;
use Module\Booking\Domain\Order\Entity\Voucher;

class GetDocumentFileInfo extends Base
{
    protected function getFileType(): string
    {
        return Voucher::class;
    }
}
