<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\Carbon;
use Module\Booking\Common\Domain\Entity\Concerns\HasStatusesTrait;
use Module\Booking\Common\Domain\Event\BookingRequestSent;
use Module\Booking\Common\Domain\Event\CancellationRequestSent;
use Module\Booking\Common\Domain\Event\ChangeRequestSent;
use Module\Booking\Common\Domain\Exception\NotRequestableEntity;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Booking extends AbstractAggregateRoot implements
    EntityInterface,
    ReservationInterface,
    BookingRequestableInterface
{
    use HasStatusesTrait;

    public function __construct(
        private readonly Id $id,
        private readonly Id $orderId,
        private BookingStatusEnum $status,
        private readonly BookingTypeEnum $type,
        private readonly Carbon $dateCreate,
        private readonly Id $creatorId,
        private ?string $note = null,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function orderId(): Id
    {
        return $this->orderId;
    }

    public function status(): BookingStatusEnum
    {
        return $this->status;
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

    public function creatorId(): Id
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
        switch ($request->type()) {
            case RequestTypeEnum::BOOKING:
                $event = new BookingRequestSent($this, $request->id()->value());
                $this->toWaitingConfirmation();
                break;
            case RequestTypeEnum::CHANGE:
                $event = new ChangeRequestSent($this, $request->id()->value());
                $this->toWaitingConfirmation();
                break;
            case RequestTypeEnum::CANCEL:
                $event = new CancellationRequestSent($this, $request->id()->value());
                $this->toWaitingCancellation();
                break;
        }
        $this->pushEvent($event);
    }

    private function setStatus(BookingStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function canSendClientVoucher(): bool
    {
        return $this->status === BookingStatusEnum::CONFIRMED;
    }
}
