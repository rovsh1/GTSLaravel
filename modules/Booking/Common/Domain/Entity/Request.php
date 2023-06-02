<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Module\Shared\Domain\Entity\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Request extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $fileGuid
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function fileGuid(): string
    {
        return $this->fileGuid;
    }
}
