<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Voucher\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Domain\Voucher\ValueObject\VoucherId;
use Module\Booking\Shared\Domain\Voucher\Voucher;
use Sdk\Booking\ValueObject\BookingId;
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
