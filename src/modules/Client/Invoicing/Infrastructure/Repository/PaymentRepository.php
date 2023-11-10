<?php

namespace Module\Client\Invoicing\Infrastructure\Repository;

use Module\Client\Invoicing\Domain\Payment\Payment;
use Module\Client\Invoicing\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Invoicing\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentAmount;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentDocument;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentId;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentStatusEnum;
use Module\Client\Invoicing\Infrastructure\Models\Payment as Model;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\ValueObject\File;
use Sdk\Module\Support\DateTimeImmutable;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function create(
        ClientId $clientId,
        PaymentStatusEnum $status,
        InvoiceNumber $invoiceNumber,
        PaymentAmount $paymentAmount,
        DateTimeImmutable $paymentDate,
        DateTimeImmutable $issuedDate,
        ?PaymentDocument $document,
    ): Payment {
        $model = Model::create([
            'client_id' => $clientId->value(),
            'status' => $status->value,
            'invoice_number' => $invoiceNumber->value(),
            'payment_currency' => $paymentAmount->currency()->value,
            'payment_sum' => $paymentAmount->sum(),
            'payment_method_id' => $paymentAmount->methodId(),
            'payment_date' => $paymentDate->format('Y-m-d'),
            'issue_date' => $issuedDate->format('Y-m-d'),
            'document_name' => $document?->name(),
            'document' => $document?->file()->guid(),
        ]);

        return $this->fromModel($model);
    }

    public function findOrFail(PaymentId $id): Payment
    {
        return $this->find($id) ?? throw new \Exception('Payment not found');
    }

    public function find(PaymentId $id): ?Payment
    {
        $model = Model::withPlantSum()
            ->where('id', $id->value())
            ->first();

        return $model ? $this->fromModel($model) : null;
    }

    public function store(Payment $payment): void
    {
        $model = Model::find($payment->id()->value());
        $model->status = $payment->status()->value;
        $model->touch();
        $model->save();
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
            paymentAmount: new PaymentAmount(
                $model->payment_currency,
                $model->payment_sum,
                $model->payment_method_id
            ),
            plantedSum: $model->planted_sum ?? 0.0,
            document: $model->document ? new PaymentDocument(
                $model->document_name,
                new File($model->document)
            ) : null,
        );
    }
}
