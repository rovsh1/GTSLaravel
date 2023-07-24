<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Voucher;

use Module\Booking\Common\Application\Support\UseCase\Admin\Request\GetDocumentFileInfo as Base;
use Module\Booking\Common\Domain\Entity\Voucher;

class GetDocumentFileInfo extends Base
{
    protected function getFileType(): string
    {
        return Voucher::class;
    }
}
