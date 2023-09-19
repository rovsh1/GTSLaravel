<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Domain\Invoice\Event\InvoiceStatusChanged;
use Module\Booking\Domain\Invoice\Service\InvoiceAmountBuilder;
use Module\Booking\Domain\Invoice\ValueObject\ClientPaymentCollection;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceAmountCollection;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceId;
use Module\Booking\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Booking\Domain\Invoice\ValueObject\StatusEnum;
use Module\Booking\Domain\Invoice\ValueObject\SupplierPaymentCollection;
use Module\Shared\ValueObject\File;
use Module\Shared\ValueObject\Timestamps;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Invoice extends AbstractAggregateRoot
{
    /**
     * @param InvoiceId $id
     * @param OrderIdCollection $orders Идентификаторы заказов ивойса
     * @param StatusEnum $status
     * @param InvoiceAmountCollection $clientAmounts Суммы к оплате для клиента (на каждую бронь)
     * @param ClientPaymentCollection $clientPayments Оплаты клиента
     * @param InvoiceAmountCollection $supplierAmounts Суммы оплат поставщикам (на каждую бронь)
     * @param SupplierPaymentCollection $supplierPayments Оплаты поставщикам
     * @param File $document Pdf файл
     * @param Timestamps $timestamps
     */
    public function __construct(
        private readonly InvoiceId $id,
        private readonly OrderIdCollection $orders,
        private StatusEnum $status,
        private InvoiceAmountCollection $clientAmounts,
        private InvoiceAmountCollection $supplierAmounts,
        private readonly File $document,
        private readonly Timestamps $timestamps
    ) {
    }

    public function id(): InvoiceId
    {
        return $this->id;
    }

    public function orders(): OrderIdCollection
    {
        return $this->orders;
    }

    public function status(): StatusEnum
    {
        return $this->status;
    }

    public function clientAmounts(): InvoiceAmountCollection
    {
        return $this->clientAmounts;
    }

    public function supplierAmounts(): InvoiceAmountCollection
    {
        return $this->supplierAmounts;
    }

    public function document(): File
    {
        return $this->document;
    }

    public function timestamps(): Timestamps
    {
        return $this->timestamps;
    }

    public function updateClientAmount(BookingId $bookingId, float $paidSum): void
    {
        $this->clientAmounts = $this->clientAmounts->updatePaid($bookingId, $paidSum);

        $this->onPaymentStateUpdated();
    }

    public function updateClientPayment(BookingId $bookingId, float $paidSum): void
    {
        $this->clientAmounts = $this->clientAmounts->updatePaid($bookingId, $paidSum);

        $this->onPaymentStateUpdated();
    }

    public function updateSupplierPayment(BookingId $bookingId, float $paidSum): void
    {
        $this->supplierAmounts = $this->supplierAmounts->updatePaid($bookingId, $paidSum);

        $this->onPaymentStateUpdated();
    }

    private function onPaymentStateUpdated(): void
    {
        $this->updateStatus(
            StatusEnum::fromSumCompare($this->clientAmounts->paidSum(), $this->clientAmounts->amountSum())
        );
    }

    private function updateStatus(StatusEnum $status): void
    {
        if ($status === $this->status) {
            return;
        }

        $beforeStatus = $this->status;
        $this->status = $status;
        $this->pushEvent(new InvoiceStatusChanged($this, $beforeStatus));
    }
}
