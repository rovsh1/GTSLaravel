<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Application\Request\AddTouristDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class AddTourist implements UseCaseInterface
{
    public function execute(AddTouristDto $request): void {}
}
