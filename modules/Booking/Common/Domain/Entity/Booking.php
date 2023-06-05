<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\Carbon;
use Module\Booking\Common\Domain\Event\BookingRequestSent;
use Module\Booking\Common\Domain\Event\CancellationRequestSent;
use Module\Booking\Common\Domain\Event\ChangeRequestSent;
use Module\Booking\Common\Domain\Exception\InvalidStatusTransition;
use Module\Booking\Common\Domain\Exception\NotRequestableEntity;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\Service\StatusRules\StatusRulesInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\Event\BookingStatusChanged;
use Module\Shared\Domain\Entity\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Booking extends AbstractAggregateRoot implements
    EntityInterface,
    ReservationInterface,
    BookingRequestableInterface
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
        $this->forceChangeStatus($status);
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
    public function generateRequest(RequestRules $requestRules, RequestCreator $requestCreator): void
    {
        if (!$requestRules->isRequestableStatus($this->status)) {
            throw new NotRequestableStatus("Booking status [{$this->status->value}] not requestable.");
        }

        $request = $requestCreator->create($this, $requestRules);
        $event = new ChangeRequestSent();
        if ($requestRules->canSendCancellationRequest($this->status)) {
            $event = new CancellationRequestSent();
        } elseif ($requestRules->canSendBookingRequest($this->status)) {
            $event = new BookingRequestSent($this->id, $request->id());
        }
        $this->pushEvent($event);

        $this->forceChangeStatus(
            $requestRules->getNextStatus($this->status)
        );
    }

    public function canSendClientVoucher(): bool
    {
        return $this->status === BookingStatusEnum::CONFIRMED;
    }

    private function forceChangeStatus(BookingStatusEnum $status): void
    {
        $oldStatus = $this->status;
        $this->status = $status;
        $this->pushEvent(new BookingStatusChanged($this, $oldStatus));
    }
}
