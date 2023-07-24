<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\Invoice;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class InvoiceFactory extends AbstractEntityFactory
{
    protected string $entity = Invoice::class;

    protected function fromArray(array $data): Invoice
    {
        return new $this->entity(
            new Id($data['id']),
            new Id($data['booking_id']),
            new CarbonImmutable($data['created_at']),
        );
    }
}
