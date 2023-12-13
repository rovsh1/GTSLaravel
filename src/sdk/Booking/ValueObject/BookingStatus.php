<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject;

use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Contracts\Support\SerializableInterface;

class BookingStatus implements SerializableInterface
{
    private readonly StatusEnum $value;
    private readonly ?string $notConfirmedReason;

    public function __construct(StatusEnum $value, ?string $notConfirmedReason)
    {
        $this->validate($value, $notConfirmedReason);
        $this->value = $value;
        $this->notConfirmedReason = $notConfirmedReason;
    }

    public static function createFromEnum(StatusEnum $status)
    {
        return new static($status, null);
    }

    public static function createNotConfirmed(string $notConfirmedReason)
    {
        return new static(StatusEnum::NOT_CONFIRMED, $notConfirmedReason);
    }

    public function value(): StatusEnum
    {
        return $this->value;
    }

    public function reason(): ?string
    {
        return $this->notConfirmedReason;
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
            'notConfirmedReason' => $this->notConfirmedReason
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            StatusEnum::from($payload['value']),
            $payload['notConfirmedReason'],
        );
    }
}
