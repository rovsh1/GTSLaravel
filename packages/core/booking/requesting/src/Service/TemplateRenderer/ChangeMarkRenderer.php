<?php

namespace Pkg\Booking\Requesting\Service\TemplateRenderer;

use Module\Booking\Shared\Domain\Booking\Booking;
use Pkg\Booking\Requesting\Domain\Service\ChangesStorageInterface;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;

class ChangeMarkRenderer
{
    private Booking $booking;

    private bool $changedFlag = false;

    private array $cached = [];

    public function __construct(
        private readonly ChangesStorageInterface $changesStorage
    ) {}

    public function boot(Booking $booking): void
    {
        $this->booking = $booking;
    }

    public function reset(): void
    {
        unset($this->booking);
        $this->cached = [];
    }

    public function changed(string $field): bool
    {
        if (!isset($this->cached[$field])) {
            $this->cached[$field] = $this->changesStorage->exists(
                new ChangesIdentifier(
                    $this->booking->id()->value(),
                    $field
                )
            );
        }

        return $this->changedFlag = $this->cached[$field];
    }

    public function endChanged(): bool
    {
        return $this->changedFlag;
    }
}