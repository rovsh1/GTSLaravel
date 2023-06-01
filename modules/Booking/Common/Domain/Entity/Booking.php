<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\Carbon;
use Module\Booking\Common\Domain\Exception\InvalidStatusTransition;
use Module\Booking\Common\Domain\Exception\NotRequestableEntity;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\StatusRules\RequestRulesInterface;
use Module\Booking\Common\Domain\Service\StatusRules\StatusRulesInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Shared\Domain\Entity\EntityInterface;

class Booking implements EntityInterface, ReservationInterface
{
    public function __construct(
        private readonly int $id,
        private readonly int $orderId,
        private BookingStatusEnum $status,
        private readonly BookingTypeEnum $type,
        private readonly Carbon $dateCreate,
        private readonly int $creatorId,
        private ?string $note = null,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function orderId(): int
    {
        return $this->orderId;
    }

    public function status(): BookingStatusEnum
    {
        return $this->status;
    }

    public function updateStatus(BookingStatusEnum $status, StatusRulesInterface $rules): void
    {
        if (!$rules->canTransit($this->status, $status)) {
            throw new InvalidStatusTransition("Can't change status for booking [{$this->id}]]");
        }
        $this->status = $status;
    }

    public function type(): BookingTypeEnum
    {
        return $this->type;
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function dateCreate(): Carbon
    {
        return $this->dateCreate;
    }

    public function creatorId(): int
    {
        return $this->creatorId;
    }

    public function setNote(string|null $note): void
    {
        $this->note = $note;
    }

    /**
     * @param RequestRulesInterface $requestRules
     * @return void
     * @throws NotRequestableStatus
     * @throws NotRequestableEntity
     */
    public function generateRequest(RequestRulesInterface $requestRules): void
    {
        if (!$requestRules->isRequestableStatus($this->status)) {
            throw new NotRequestableStatus("Booking status [{$this->status->value}] not requestable.");
        }
        //@todo прикрепить куда-то документ
        $documentContent = $requestRules->getDocumentGenerator($this)->generate($this);

        $nextStatus = $requestRules->getNextStatus($this->status);
        $this->status = $nextStatus;
    }
}
