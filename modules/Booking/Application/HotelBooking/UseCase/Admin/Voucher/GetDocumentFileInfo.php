<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin\Voucher;

use Module\Booking\Application\Shared\Support\UseCase\Admin\Request\GetDocumentFileInfo as Base;
use Module\Booking\Domain\Shared\Entity\Voucher;

class GetDocumentFileInfo extends Base
{
    protected function getFileType(): string
    {
        return Voucher::class;
    }
}
