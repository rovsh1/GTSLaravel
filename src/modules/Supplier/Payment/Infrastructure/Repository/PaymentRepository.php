<?php

namespace Module\Supplier\Payment\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Module\Supplier\Payment\Domain\Payment\Payment;
use Module\Supplier\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Supplier\Payment\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Supplier\Payment\Domain\Payment\ValueObject\Landing;
use Module\Supplier\Payment\Domain\Payment\ValueObject\LandingCollection;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentDocument;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Module\Supplier\Payment\Infrastructure\Models\Landing as LandingModel;
use Module\Supplier\Payment\Infrastructure\Models\Payment as Model;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\SupplierId;
use Sdk\Module\Support\DateTimeImmutable;
use Sdk\Shared\ValueObject\File;
use Sdk\Shared\ValueObject\Money;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function findOrFail(PaymentId $id): Payment
    {
        return $this->find($id) ?? throw new \Exception('Payment not found');
    }

    public function find(PaymentId $id): ?Payment
    {
        $model = Model::withLandings()
            ->where('id', $id->value())
            ->first();

        return $model ? $this->fromModel($model) : null;
    }

    public function store(Payment $payment): void
    {
        $paymentId = $payment->id()->value();
        $model = Model::find($paymentId);
        $model->status = $payment->status()->value;
        $model->touch();
        $model->save();

        if ($payment->landings()->count() === 0) {
            LandingModel::wherePaymentId($paymentId)->delete();

            return;
        }

        $bookingIds = $payment->landings()->map(fn(Landing $landing) => $landing->bookingId()->value());
        LandingModel::wherePaymentId($paymentId)->whereNotIn('booking_id', $bookingIds)->delete();

        $landingModels = $payment->landings()->map(fn(Landing $landing) => [
            'payment_id' => $paymentId,
            'booking_id' => $landing->bookingId()->value(),
            'sum' => $landing->sum(),
            'created_at' => now(),
        ]);
        LandingModel::upsert($landingModels, ['payment_id', 'booking_id'], ['sum', 'created_at']);
    }

    /**
     * @param OrderId $id
     * @return Payment[]
     */
    public function findByBookingId(BookingId $id): array
    {
        $payments = Model::withLandings()->whereBookingId($id->value())->get();

        return $payments->map(fn(Model $model) => $this->fromModel($model))->all();
    }

    private function fromModel(Model $model): Payment
    {
        return new Payment(
            id: new PaymentId($model->id),
            supplierId: new SupplierId($model->supplier_id),
            status: $model->status,
            invoiceNumber: new InvoiceNumber($model->invoice_number),
            issueDate: DateTimeImmutable::createFromMutable($model->issue_date),
            paymentDate: DateTimeImmutable::createFromMutable($model->payment_date),
            paymentAmount: new Money(
                $model->payment_currency,
                $model->payment_sum,
            ),
            landings: $this->buildLandings($model->landings),
            document: $model->document ? new PaymentDocument(
                $model->document_name,
                new File($model->document)
            ) : null,
        );
    }

    private function buildLandings(Collection $landings): LandingCollection
    {
        $entities = $landings->map(fn(LandingModel $landing) => new Landing(
            new BookingId($landing->booking_id),
            $landing->sum,
        ));

        return new LandingCollection($entities);
    }
}
