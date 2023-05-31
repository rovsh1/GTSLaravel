<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Factory;

use Module\Booking\Common\Application\Response\StatusDto;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Shared\Domain\Service\TranslatorInterface;

class StatusDtoFactory
{
    public function __construct(private readonly TranslatorInterface $translator) {}

    public function build(BookingStatusEnum $status): StatusDto
    {
        $name = $this->translator->translateEnum($status);
        return new StatusDto($status->value, $name, $status->name);
    }
}
