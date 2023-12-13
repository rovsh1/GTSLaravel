<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject;

use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Contracts\Support\SerializableInterface;

class BookingStatus implements SerializableInterface
{
    private readonly StatusEnum $value;
    private readonly ?string $reason;

    private function __construct(StatusEnum $value, ?string $reason)
    {
        $this->validate($value, $reason);
        $this->value = $value;
        $this->reason = $reason;
    }

    public static function createFromEnum(StatusEnum $status)
    {
        return new static($status, null);
    }

    public static function createNotConfirmed(string $reason)
    {
        return new static(StatusEnum::NOT_CONFIRMED, $reason);
    }

    public function value(): StatusEnum
    {
        return $this->value;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    private function validate(StatusEnum $status, ?string $reason): void
    {
        if ($status === StatusEnum::NOT_CONFIRMED && empty($reason)) {
            throw new \InvalidArgumentException('Not confirmed status reason required');
        }
    }

    public function serialize(): array
    {
        return [
            'value' => $this->value->value,
            'reason' => $this->reason
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            StatusEnum::from($payload['value']),
            $payload['reason'],
        );
    }
}
