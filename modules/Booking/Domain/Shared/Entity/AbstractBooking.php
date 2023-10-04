<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\Entity\Concerns\HasStatusesTrait;
use Module\Booking\Domain\Shared\Event\BookingDeleted;
use Module\Booking\Domain\Shared\Event\BookingPriceChanged;
use Module\Booking\Domain\Shared\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Domain\Shared\Event\Request\BookingRequestSent;
use Module\Booking\Domain\Shared\Event\Request\CancellationRequestSent;
use Module\Booking\Domain\Shared\Event\Request\ChangeRequestSent;
use Module\Booking\Domain\Shared\Exception\NotRequestableEntity;
use Module\Booking\Domain\Shared\Exception\NotRequestableStatus;
use Module\Booking\Domain\Shared\Service\BookingCalculatorInterface;
use Module\Booking\Domain\Shared\Service\BookingPriceChangeDecorator;
use Module\Booking\Domain\Shared\Service\RequestCreator;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Booking\Domain\Shared\ValueObject\PriceItem;
use Module\Booking\Domain\Shared\ValueObject\RequestTypeEnum;
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
        private BookingPrice $price,
    ) {}

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

    abstract public function type(): BookingTypeEnum;

    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function creatorId(): CreatorId
    {
        return $this->creatorId;
    }

    public function price(): BookingPrice
    {
        return $this->price;
    }

    public function recalculatePrices(BookingCalculatorInterface $calculator): void
    {
        $grossPrice = $this->price->grossPrice()->manualValue() !== null
            ? $this->price->grossPrice()
            : $calculator->calculateGrossPrice($this);

        $netPrice = $this->price->netPrice()->manualValue() !== null
            ? $this->price->netPrice()
            : $calculator->calculateNetPrice($this);

        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setPrices($netPrice, $grossPrice);
        $this->changePrice($priceBuilder);
    }

    public function setGrossPriceManually(float $price): void
    {
        $grossPrice = new PriceItem(
            currency: $this->price->grossPrice()->currency(),
            calculatedValue: $this->price->grossPrice()->calculatedValue(),
            penaltyValue: $this->price->grossPrice()->penaltyValue(),
            manualValue: $price,
        );
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setGrossPrice($grossPrice);
        $this->changePrice($priceBuilder);
    }

    public function setNetPriceManually(float $price): void
    {
        $netPrice = new PriceItem(
            currency: $this->price->netPrice()->currency(),
            calculatedValue: $this->price->netPrice()->calculatedValue(),
            penaltyValue: $this->price->netPrice()->penaltyValue(),
            manualValue: $price,
        );
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setNetPrice($netPrice);
        $this->changePrice($priceBuilder);
    }

    public function setCalculatedPrices(BookingCalculatorInterface $calculator): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setGrossPrice(
            $calculator->calculateGrossPrice($this)
        );
        $priceBuilder->setNetPrice(
            $calculator->calculateNetPrice($this)
        );
        $this->changePrice($priceBuilder);
    }

    public function setCalculatedGrossPrice(BookingCalculatorInterface $calculator): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setGrossPrice(
            $calculator->calculateGrossPrice($this)
        );
        $this->changePrice($priceBuilder);
    }

    public function setCalculatedNetPrice(BookingCalculatorInterface $calculator): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setNetPrice(
            $calculator->calculateNetPrice($this)
        );
        $this->changePrice($priceBuilder);
    }

    public function setGrossPenalty(float|null $amount): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setGrossPenalty($amount);
        $this->changePrice($priceBuilder);
    }

    public function setNetPenalty(float|null $amount): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setNetPenalty($amount);
        $this->changePrice($priceBuilder);
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

    public function isManualGrossPrice(): bool
    {
        return $this->price()->grossPrice()->manualValue() !== null;
    }

    public function isManualNetPrice(): bool
    {
        return $this->price()->netPrice()->manualValue() !== null;
    }

    public function delete(): void
    {
        $this->setStatus(BookingStatusEnum::DELETED);
        $this->pushEvent(
            new BookingDeleted($this)
        );
    }

    private function setStatus(BookingStatusEnum $status): void
    {
        $this->status = $status;
    }

    private function changePrice(BookingPriceChangeDecorator $priceBuilder): void
    {
        if (!$priceBuilder->isPriceChanged()) {
            return;
        }
        $this->price = $priceBuilder->getPriceAfter();
        $this->pushEvent(
            new BookingPriceChanged(
                $this,
                $priceBuilder->getPriceBefore(),
            )
        );
    }
}
