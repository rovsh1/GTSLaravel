<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase;

use Module\Booking\Common\Application\Dto\StatusDto;
use Module\Booking\Common\Application\Factory\StatusDtoFactory;
use Module\Booking\Common\Application\Support\UseCase\GetStatuses as Base;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatuses extends Base
{

}
