<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\ValueObject;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class CancelMarkupOption implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly Percent $percent,
    ) {}

    public function percent(): Percent
    {
        return $this->percent;
    }

    public function toData(): array
    {
        return [
            'percent' => $this->percent->value(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            new Percent($data['percent']),
        );
    }
}
