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
        private readonly ?File $wordFile,
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

    public function wordFile(): ?File
    {
        return $this->wordFile;
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
            'wordFile' => $this->wordFile?->guid(),
            'sendAt' => $this->sendAt?->getTimestamp(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        $sendAt = $payload['sendAt'] ?? null;
        $wordFile = $payload['wordFile'] ?? null;

        return new static(
            DateTime::createFromTimestamp($payload['createdAt'])->toDateTimeImmutable(),
            new File($payload['file']),
            $wordFile !== null ? new File($wordFile) : null,
            $sendAt !== null ? DateTime::createFromTimestamp($sendAt)->toDateTimeImmutable() : null,
        );
    }
}
