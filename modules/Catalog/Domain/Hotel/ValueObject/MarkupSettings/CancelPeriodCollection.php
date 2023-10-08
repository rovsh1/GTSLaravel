<?php

declare(strict_types=1);

namespace Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings;

use Illuminate\Support\Collection;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

/**
 * @extends Collection<int, CancelPeriod>
 */
final class CancelPeriodCollection extends Collection implements ValueObjectInterface, SerializableDataInterface
{
    public function toData(): array
    {
        return $this->map(fn(CancelPeriod $cancelPeriod) => $cancelPeriod->toData())->values()->all();
    }

    public static function fromData(array $data): static
    {
        return (new static($data))->map(fn(array $item) => CancelPeriod::fromData($item));
    }
}
