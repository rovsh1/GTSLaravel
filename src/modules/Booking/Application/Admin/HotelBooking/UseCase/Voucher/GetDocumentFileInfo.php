<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase\Voucher;

use Module\Booking\Application\Admin\Shared\Support\UseCase\Request\GetDocumentFileInfo as Base;
use Module\Booking\Domain\Shared\Entity\Voucher;

class GetDocumentFileInfo extends Base
{
    protected function getFileType(): string
    {
        return Voucher::class;
    }
}
