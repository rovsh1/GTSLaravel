<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\Concerns\HasStatusesTrait;
use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Common\Domain\Event\Request\BookingRequestSent;
use Module\Booking\Common\Domain\Event\Request\CancellationRequestSent;
use Module\Booking\Common\Domain\Event\Request\ChangeRequestSent;
use Module\Booking\Common\Domain\Exception\NotRequestableEntity;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Booking\Hotel\Domain\ValueObject\ManualChangablePrice;
use Module\Booking\PriceCalculator\Domain\Service\BookingCalculatorInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

abstract class AbstractBooking extends AbstractAggregateRoot implements
    BookingInterface,
    BookingRequestableInterface
{
    use HasStatusesTrait;

    public function __construct(
        private readonly Id $id,
        private readonly Id $orderId,
        private BookingStatusEnum $status,
        private readonly CarbonImmutable $createdAt,
        private readonly Id $creatorId,
        private BookingPrice $price,
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

    abstract public function type(): BookingTypeEnum;

    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function creatorId(): Id
    {
        return $this->creatorId;
    }

    public function price(): BookingPrice
    {
        return $this->price;
    }

    public function setBoPriceManually(float $price): void
    {
        $this->price = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: new ManualChangablePrice($price, true),
            hoPrice: $this->price->hoPrice()
        );
    }

    public function setHoPriceManually(float $price): void
    {
        $this->price = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: $this->price->boPrice(),
            hoPrice: new ManualChangablePrice($price, true),
        );
    }

    public function setCalculatedPrices(BookingCalculatorInterface $calculator): void
    {
        $this->price = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: new ManualChangablePrice(
                $calculator->calculateBoPrice($this)
            ),
            hoPrice: new ManualChangablePrice(
                $calculator->calculateHoPrice($this)
            ),
        );
    }

    public function setCalculatedBoPrice(BookingCalculatorInterface $calculator): void
    {
        $this->price = new BookingPrice(
            netValue: $this->price->netValue(),
            boPrice: new ManualChangablePrice(
                $calculator->calculateBoPrice($this)
            ),
            hoPrice: $this->price()->hoPrice()
        );
    }

    public function setCalculatedHoPrice(BookingCalculatorInterface $calculator): void
    {
        $this->price = new BookingPrice(
            netValue: $this->price->netValue(),
            hoPrice: new ManualChangablePrice(
                $calculator->calculateHoPrice($this)
            ),
            boPrice: $this->price()->boPrice()
        );
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

    public function isManualBoPrice(): bool
    {
        //@todo уточнить у Сергея по поводу boValue
        return $this->price()->boPrice()->isManual();
    }

    public function canSendClientVoucher(): bool
    {
        return $this->status === BookingStatusEnum::CONFIRMED;
    }

    private function setStatus(BookingStatusEnum $status): void
    {
        $this->status = $status;
    }
}
