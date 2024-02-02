<?php

namespace Module\Client\Payment\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Client\Payment\Domain\Payment\Payment;
use Module\Client\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Payment\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Client\Payment\Domain\Payment\ValueObject\Landing;
use Module\Client\Payment\Domain\Payment\ValueObject\LandingCollection;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentDocument;
use Module\Client\Payment\Infrastructure\Models\Landing as LandingModel;
use Module\Client\Payment\Infrastructure\Models\Payment as Model;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\PaymentId;
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

        $orderIds = $payment->landings()->map(fn(Landing $landing) => $landing->orderId()->value());
        LandingModel::wherePaymentId($paymentId)->whereNotIn('order_id', $orderIds)->delete();

        $landingModels = $payment->landings()->map(fn(Landing $landing) => [
            'payment_id' => $paymentId,
            'order_id' => $landing->orderId()->value(),
            'sum' => $landing->sum(),
            'created_at' => now(),
        ]);
        LandingModel::upsert($landingModels, ['payment_id', 'order_id'], ['sum', 'created_at']);
    }

    /**
     * @param OrderId $id
     * @return Payment[]
     */
    public function findByOrderId(OrderId $id): array
    {
        $payments = Model::withLandings()->whereOrderId($id->value())->get();

        return $payments->map(fn(Model $model) => $this->fromModel($model))->all();
    }

    private function fromModel(Model $model): Payment
    {
        return new Payment(
            id: new PaymentId($model->id),
            clientId: new ClientId($model->client_id),
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
            new OrderId($landing->order_id),
            $landing->sum,
        ));

        return new LandingCollection($entities);
    }
}
