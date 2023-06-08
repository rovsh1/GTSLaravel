<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Common\Application\Factory\StatusDtoFactory;
use Module\Booking\Common\Application\Support\UseCase\Admin\GetAvailableActions as Base;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;

class GetAvailableActions extends Base
{
    public function __construct(
//        StatusRulesInterface $statusRules,
        RequestRules $requestRules,
        BookingRepositoryInterface $repository,
        StatusDtoFactory $factory
    ) {
        //@todo тут пробрасываются рулзы вручную пока
        parent::__construct(new AdministratorRules(), $requestRules, $repository, $factory);
    }
}
