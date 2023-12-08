<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\ValueObject;

use Sdk\Module\Support\DateTime;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\File;

class Voucher implements SerializableInterface
{
    public function __construct(
        private readonly \DateTimeImmutable $createdAt,
        private readonly File $file,
        private readonly ?\DateTimeImmutable $sendAt,
    ) {}

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function file(): File
    {
        return $this->file;
    }

    public function sendAt(): ?\DateTimeImmutable
    {
        return $this->sendAt;
    }

    public function serialize(): array
    {
        return [
            'createdAt' => $this->createdAt->getTimestamp(),
            'file' => $this->file->guid(),
            'sendAt' => $this->sendAt?->getTimestamp(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        $sendAt = $payload['sendAt'] ?? null;

        return new static(
            DateTime::createFromTimestamp($payload['createdAt'])->toDateTimeImmutable(),
            new File($payload['file']),
            $sendAt !== null ? DateTime::createFromTimestamp($sendAt)->toDateTimeImmutable() : null,
        );
    }
}
