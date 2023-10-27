<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\UseCase\Admin;

use Module\Booking\Application\Admin\Shared\Factory\StatusDtoFactory;
use Module\Booking\Application\Admin\Shared\Support\UseCase\GetAvailableActions as Base;
use Module\Booking\Deprecated\TransferBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Booking\Domain\Shared\Service\StatusRules\AdministratorRules;

class GetAvailableActions extends Base
{
    public function __construct(
//        StatusRulesInterface $statusRules,
        RequestRules $requestRules,
        BookingRepositoryInterface $repository,
        StatusDtoFactory $statusStorage
    ) {
        //@todo тут пробрасываются рулзы вручную пока
        parent::__construct(new AdministratorRules($requestRules), $requestRules, $repository, $statusStorage);
    }
}
