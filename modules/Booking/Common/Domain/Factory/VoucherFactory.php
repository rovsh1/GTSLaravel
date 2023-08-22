<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\Voucher;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\VoucherId;
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
