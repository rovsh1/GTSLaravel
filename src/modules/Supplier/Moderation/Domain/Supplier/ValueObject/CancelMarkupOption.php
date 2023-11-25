<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\Percent;

final class CancelMarkupOption implements ValueObjectInterface, SerializableInterface
{
    public function __construct(
        private readonly Percent $percent,
    ) {}

    public function percent(): Percent
    {
        return $this->percent;
    }

    public function serialize(): array
    {
        return [
            'percent' => $this->percent->value(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            new Percent($payload['percent']),
        );
    }
}
