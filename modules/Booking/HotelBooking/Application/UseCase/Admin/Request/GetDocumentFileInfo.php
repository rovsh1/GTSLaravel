<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Request;

use Module\Booking\Common\Application\Support\UseCase\Admin\Request\GetDocumentFileInfo as Base;
use Module\Booking\Common\Domain\Entity\Request;

class GetDocumentFileInfo extends Base
{
    protected function getFileType(): string
    {
        return Request::class;
    }
}
