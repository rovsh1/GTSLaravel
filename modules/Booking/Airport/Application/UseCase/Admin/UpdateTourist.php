<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Application\Request\AddTouristDto;
use Module\Booking\Airport\Application\Request\UpdateTouristDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateTourist implements UseCaseInterface
{
    public function execute(UpdateTouristDto $request): void {}
}
