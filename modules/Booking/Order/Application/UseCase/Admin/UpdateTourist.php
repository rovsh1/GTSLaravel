<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin;

use Module\Booking\Order\Application\Request\UpdateTouristDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateTourist implements UseCaseInterface
{
    public function execute(UpdateTouristDto $request): void {}
}
