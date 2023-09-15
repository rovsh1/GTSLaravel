<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\Concerns\HasStatusesTrait;
use Module\Booking\Common\Domain\Event\BookingDeleted;
use Module\Booking\Common\Domain\Event\BookingPriceChanged;
use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;
use Module\Booking\Common\Domain\Event\Request\BookingRequestSent;
use Module\Booking\Common\Domain\Event\Request\CancellationRequestSent;
use Module\Booking\Common\Domain\Event\Request\ChangeRequestSent;
use Module\Booking\Common\Domain\Exception\NotRequestableEntity;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\BookingCalculatorInterface;
use Module\Booking\Common\Domain\Service\BookingPriceChangeDecorator;
use Module\Booking\Common\Domain\Service\InvoiceCreator;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\Service\VoucherCreator;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPriceNew;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Common\Domain\ValueObject\PriceItem;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Shared\Domain\ValueObject\Id;
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
        private readonly Id $creatorId,
        private BookingPriceNew $price,
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

    public function creatorId(): Id
    {
        return $this->creatorId;
    }

    public function price(): BookingPriceNew
    {
        return $this->price;
    }

    public function recalculatePrices(BookingCalculatorInterface $calculator): void
    {
        $grossPrice = $this->price->grossPrice()->manualValue() !== null
            ? $this->price->grossPrice()
            : $calculator->calculateGrossPrice($this);

        $netPrice = $this->price->netPrice()->manualValue() !== null
            ? $this->price->grossPrice()
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

    public function generateVoucher(VoucherCreator $voucherCreator): void
    {
        $voucherCreator->create($this);
        //@todo кинуть ивент
    }

    public function generateInvoice(InvoiceCreator $invoiceCreator): void
    {
        $invoiceCreator->create($this);
        //@todo кинуть ивент
    }

    public function isManualGrossPrice(): bool
    {
        return $this->price()->grossPrice()->manualValue() !== null;
    }

    public function isManualNetPrice(): bool
    {
        return $this->price()->netPrice()->manualValue() !== null;
    }

    public function canSendClientVoucher(): bool
    {
        return $this->status === BookingStatusEnum::CONFIRMED;
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
