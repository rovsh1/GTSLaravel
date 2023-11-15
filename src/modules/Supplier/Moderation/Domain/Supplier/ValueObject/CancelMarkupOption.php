<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;

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
