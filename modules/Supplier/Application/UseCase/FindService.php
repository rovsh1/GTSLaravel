<?php

declare(strict_types=1);

namespace Module\Supplier\Application\UseCase;

use Module\Shared\Enum\ServiceTypeEnum;
use Module\Supplier\Application\Response\ServiceDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindService implements UseCaseInterface
{
    public function execute(int $id): ?ServiceDto
    {
        return new ServiceDto(
            2,
            'Трансфер в аэропорт',
            ServiceTypeEnum::TRANSFER_TO_AIRPORT
        );
    }
}
