<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\UseCase;

use Module\Hotel\Moderation\Application\Admin\Query\GetHotelMarkupSettings;
use Module\Hotel\Moderation\Application\Admin\Response\MarkupSettingsDto;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetMarkupSettings implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function execute(int $id): MarkupSettingsDto
    {
        return $this->queryBus->execute(new GetHotelMarkupSettings($id));
    }
}
