<?php

namespace Module\Booking\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\Domain\Invoice\Event\InvoiceCreated;
use Module\Booking\Domain\Invoice\Invoice;
use Module\Booking\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceId;
use Module\Booking\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Booking\Domain\Invoice\ValueObject\StatusEnum;
use Module\Booking\Infrastructure\Factory\InvoiceFactory;
use Module\Shared\ValueObject\File;
use Module\Booking\Infrastructure\Model\Invoice\Invoice as Model;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $domainEventDispatcher,
        private readonly InvoiceFactory $invoiceFactory,
    ) {
    }

    public function create(OrderIdCollection $orders, File $document): Invoice
    {
        $invoice = DB::transaction(function () use ($orders, $document) {
            $model = Model::create([
                'status' => StatusEnum::NOT_PAID->value,
                'file' => $document->guid()
            ]);

            return $this->invoiceFactory->createFromModel($model);
        });

        $this->domainEventDispatcher->dispatch(new InvoiceCreated($invoice));

        return $invoice;
    }

    public function find(InvoiceId $id): ?Invoice
    {
        return ($model = Model::find($id->value())) ? $this->invoiceFactory->createFromModel($model) : null;
    }

    public function store(Invoice $invoice): void
    {
        $model = Model::find($invoice->id()->value());
        if (!$model) {
            throw new \Exception();
        }

        DB::transaction(function () use ($model, $invoice) {
            $model->status = $invoice->status()->value;
            $model->touch();
            $model->save();
        });
    }
}