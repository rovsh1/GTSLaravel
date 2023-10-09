<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Booking\Support\Concerns\HasStatusesTrait;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\BookingRequest\Service\RequestCreator;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\Event\BookingDeleted;
use Module\Booking\Domain\Shared\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Domain\Shared\Event\Request\BookingRequestSent;
use Module\Booking\Domain\Shared\Event\Request\CancellationRequestSent;
use Module\Booking\Domain\Shared\Event\Request\ChangeRequestSent;
use Module\Booking\Domain\Shared\Exception\NotRequestableEntity;
use Module\Booking\Domain\Shared\Exception\NotRequestableStatus;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

abstract class AbstractBooking extends AbstractAggregateRoot implements
    BookingInterface,
    BookingRequestableInterface
{
    use HasStatusesTrait;

    public function __construct(
        private readonly BookingId $id,
        private readonly OrderId $orderId,
        private BookingStatusEnum $status,
        private readonly CarbonImmutable $createdAt,
        private readonly CreatorId $creatorId,
        private BookingPrices $price,
    ) {
    }

    public function id(): BookingId
    {
        return $this->id;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function status(): BookingStatusEnum
    {
        return $this->status;
    }

    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function creatorId(): CreatorId
    {
        return $this->creatorId;
    }

    public function price(): BookingPrices
    {
        return $this->price;
    }

    /**
     * @param RequestRules $requestRules
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

    public function delete(): void
    {
        $this->setStatus(BookingStatusEnum::DELETED);
        $this->pushEvent(
            new BookingDeleted($this)
        );
    }

    public function updatePrice(BookingPrices $price): void
    {
        if (!$price->isEqual($this->price)) {
            return;
        }

        $priceBefore = $this->price;
        $this->price = $price;
        $this->pushEvent(new BookingPriceChanged($this, $priceBefore));
    }

    private function setStatus(BookingStatusEnum $status): void
    {
        $this->status = $status;
    }
}
