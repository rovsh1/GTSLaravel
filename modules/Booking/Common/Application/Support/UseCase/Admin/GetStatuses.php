<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Dto\StatusDto;
use Module\Booking\Common\Application\Factory\StatusDtoFactory;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatuses implements UseCaseInterface
{
    public function __construct(private readonly StatusDtoFactory $factory) {}

    /**
     * @return StatusDto[]
     */
    public function execute(): array
    {
        return array_map(fn(BookingStatusEnum $status) => $this->factory->build($status), BookingStatusEnum::cases());
    }
}
