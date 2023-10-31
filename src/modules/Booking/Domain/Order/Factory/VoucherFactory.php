<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Order\Entity\Voucher;
use Module\Booking\Domain\Order\ValueObject\VoucherId;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class VoucherFactory extends AbstractEntityFactory
{
    protected string $entity = Voucher::class;

    protected function fromArray(array $data): Voucher
    {
        return new $this->entity(
            new VoucherId($data['id']),
            new BookingId($data['booking_id']),
            new CarbonImmutable($data['created_at']),
        );
    }
}
