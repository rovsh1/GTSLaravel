<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Infrastructure\Repository;

use Module\Client\Shared\Domain\Exception\OrderNotFoundException;
use Module\Supplier\Payment\Domain\Booking\Booking;
use Module\Supplier\Payment\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Module\Supplier\Payment\Infrastructure\Models\Booking as Model;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\SupplierId;
use Sdk\Shared\ValueObject\Money;

class BookingRepository implements BookingRepositoryInterface
{
    public function find(BookingId $id): ?Booking
    {
        $model = Model::find($id->value());
        if ($model === null) {
            return null;
        }

        return $this->fromModel($model);
    }

    public function findOrFail(BookingId $id): Booking
    {
        return $this->find($id) ?? throw new OrderNotFoundException();
    }

    public function store(Booking $booking): void
    {
//        Model::whereId($booking->id()->value())->update([
//            'bookings.status' => $booking->status(),
//        ]);
    }

    /**
     * @param PaymentId $paymentId
     * @return Booking[]
     */
    public function getForWaitingPayment(PaymentId $paymentId): array
    {
        $models = Model::forPaymentId($paymentId->value())
            ->whereIn('bookings.status', [
                StatusEnum::CONFIRMED,
                StatusEnum::CANCELLED_FEE,
            ])
            ->whereNotPaid()
            ->get();

        return $models->map(fn(Model $model) => $this->fromModel($model))->all();
    }

    /**
     * @param PaymentId $paymentId
     * @return Booking[]
     */
    public function getPaymentBookings(PaymentId $paymentId): array
    {
        $models = Model::forLandingToPaymentId($paymentId->value())->get();

        return $models->map(fn(Model $model) => $this->fromModel($model))->all();
    }

    private function fromModel(Model $model): Booking
    {
        $penalty = $model->supplier_penalty > 0 ? new Money($model->supplier_currency, $model->supplier_penalty) : null;

        return new Booking(
            id: new BookingId($model->id),
            supplierId: new SupplierId($model->supplier_id),
            status: $model->status,
            supplierPrice: new Money(
                $model->supplier_currency,
                $model->supplier_price,
            ),
            supplierPenalty: $penalty,
            payedAmount: new Money(
                $model->supplier_currency,
                $model->payed_amount,
            )
        );
    }
}
