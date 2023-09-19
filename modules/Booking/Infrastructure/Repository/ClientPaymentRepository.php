<?php

namespace Module\Booking\Infrastructure\Repository;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Domain\Invoice\Entity\ClientPayment;
use Module\Booking\Domain\Invoice\Repository\ClientPaymentRepositoryInterface;
use Module\Booking\Domain\Invoice\ValueObject\ClientPaymentId;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceId;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceNumber;
use Module\Booking\Domain\Invoice\ValueObject\PaymentAmount;
use Module\Booking\Domain\Invoice\ValueObject\PaymentDocument;
use Module\Booking\Domain\Invoice\ValueObject\PaymentMethodEnum;
use Module\Booking\Infrastructure\Model\Invoice\Payment as Model;
use Module\Shared\ValueObject\File;
use Sdk\Module\Support\DateTimeImmutable;

class ClientPaymentRepository implements ClientPaymentRepositoryInterface
{
    public function create(
        InvoiceId $invoiceId,
        BookingId $bookingId,
        InvoiceNumber $number,
        DateTimeImmutable $issuedDate,
        DateTimeImmutable $paidDate,
        PaymentAmount $paymentAmount,
        ?PaymentDocument $document,
    ): ClientPayment {
        $model = Model::create([
            'invoice_id' => $invoiceId->value(),
            'booking_id' => $bookingId->value(),
            'number' => $number->value(),
            'issued_at' => $issuedDate,
            'paid_at' => $paidDate,
            'payment_sum' => $paymentAmount->sum(),
            'payment_method' => $paymentAmount->method(),
            'document_name' => $document->name(),
            'document' => $document->file()->guid(),
        ]);

        return $this->fromModel($model);
    }

    public function find(ClientPaymentId $id): ?ClientPayment
    {
        return ($model = Model::find($id->value())) ? $this->fromModel($model) : null;
    }

    private function fromModel(Model $model): ClientPayment
    {
        return new ClientPayment(
            id: new ClientPaymentId($model->id),
            invoiceId: new InvoiceId($model->invoice_id),
            bookingId: new BookingId($model->booking_id),
            number: new InvoiceNumber($model->number),
            issuedDate: DateTimeImmutable::createFromMutable($model->issued_at),
            paidDate: DateTimeImmutable::createFromMutable($model->paid_at),
            paymentAmount: new PaymentAmount($model->payment_sum, PaymentMethodEnum::from($model->payment_method)),
            document: new PaymentDocument($model->document_name, new File($model->document)),
        );
    }
}
