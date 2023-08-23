<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin;

use Module\Booking\Order\Application\Request\AddTouristDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class AddTourist implements UseCaseInterface
{
    public function execute(AddTouristDto $request): void {}
}
