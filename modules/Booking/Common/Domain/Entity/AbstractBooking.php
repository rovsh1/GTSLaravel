<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\Concerns\HasStatusesTrait;
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
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\ManualChangablePrice;
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

    public function recalculatePrices(BookingCalculatorInterface $calculator): void
    {
        $boPrice = $this->price()->boPrice()->isManual()
            ? $this->price()->boPrice()
            : new ManualChangablePrice($calculator->calculateBoPrice($this));

        $hoPrice = $this->price()->hoPrice()->isManual()
            ? $this->price()->hoPrice()
            : new ManualChangablePrice($calculator->calculateHoPrice($this));

        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setPrices($boPrice, $hoPrice);
        $this->changePrice($priceBuilder);
    }

    public function setBoPriceManually(float $price): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setBoPrice(
            new ManualChangablePrice($price, true)
        );
        $this->changePrice($priceBuilder);
    }

    public function setHoPriceManually(float $price): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setHoPrice(
            new ManualChangablePrice($price, true)

        );
        $this->changePrice($priceBuilder);
    }

    public function setCalculatedPrices(BookingCalculatorInterface $calculator): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setBoPrice(
            new ManualChangablePrice(
                $calculator->calculateBoPrice($this)
            )
        );
        $priceBuilder->setHoPrice(
            new ManualChangablePrice(
                $calculator->calculateHoPrice($this)
            )
        );
        $this->changePrice($priceBuilder);
    }

    public function setCalculatedBoPrice(BookingCalculatorInterface $calculator): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setBoPrice(
            new ManualChangablePrice(
                $calculator->calculateBoPrice($this)
            )
        );
        $this->changePrice($priceBuilder);
    }

    public function setCalculatedHoPrice(BookingCalculatorInterface $calculator): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setHoPrice(
            new ManualChangablePrice(
                $calculator->calculateHoPrice($this)
            )
        );
        $this->changePrice($priceBuilder);
    }

    public function setBoPenalty(float|null $amount): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setBoPenalty($amount);
        $this->changePrice($priceBuilder);
    }

    public function setHoPenalty(float|null $amount): void
    {
        $priceBuilder = new BookingPriceChangeDecorator($this->price);
        $priceBuilder->setHoPenalty($amount);
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

    public function isManualBoPrice(): bool
    {
        return $this->price()->boPrice()->isManual();
    }

    public function isManualHoPrice(): bool
    {
        return $this->price()->hoPrice()->isManual();
    }

    public function canSendClientVoucher(): bool
    {
        return $this->status === BookingStatusEnum::CONFIRMED;
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
