<?php

namespace GTS\Reservation\Common\Domain\Entity;

use GTS\Reservation\Common\Domain\ValueObject\Creator;
use GTS\Reservation\Common\Domain\ValueObject\DocumentStatus;
use GTS\Reservation\Common\Domain\ValueObject\DocumentTypeEnum;
use GTS\Shared\Domain\Entity\FileInterface;

abstract class AbstractDocument implements DocumentInterface
{
    public function __construct(
        private readonly int $id,
        private readonly int $reservationId,
        private readonly DocumentStatus $status,
        private readonly Creator $creator,
        private readonly FileInterface $file,
    ) {}

    abstract public function type(): DocumentTypeEnum;

    public function id(): int
    {
        return $this->id;
    }

    public function reservationId(): int
    {
        return $this->reservationId;
    }

    public function status(): DocumentStatus
    {
        return $this->status;
    }

    public function creator(): Creator
    {
        return $this->creator;
    }

    public function file(): FileInterface
    {
        return $this->file;
    }
}
