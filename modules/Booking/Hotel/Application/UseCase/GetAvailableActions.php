<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase;

use Module\Booking\Common\Application\Dto\AvailableActionsDto;
use Module\Booking\Common\Application\Factory\StatusDtoFactory;
use Module\Booking\Common\Application\Support\UseCase\GetAvailableActions as Base;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\StatusRules\RequestRules;
use Module\Booking\Common\Domain\Service\StatusRules\Rules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions extends Base
{

}
