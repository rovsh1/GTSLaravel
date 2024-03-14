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

    private array $renderedFields = [];

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
        $this->changedFlag = false;
        $this->cached = [];
        $this->renderedFields = [];
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

    public function hasChanges(?string $field): bool
    {
        $changes = $this->changes($field);
        return count($changes) > 0;
    }

    public function changes(?string $field): array
    {
        if ($field !== null && !in_array($field, $this->renderedFields)) {
            $this->renderedFields[] = $field;
        }
        $excludeFields = $field === null && !empty($this->renderedFields) ? $this->renderedFields : null;

        return $this->changesStorage->get($this->booking->id(), $field, $excludeFields);
    }

    public function endChanged(): bool
    {
        return $this->changedFlag;
    }
}
