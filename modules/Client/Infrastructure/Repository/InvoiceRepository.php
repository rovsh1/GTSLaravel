<?php

namespace Module\Client\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Client\Domain\Invoice\Event\InvoiceCreated;
use Module\Client\Domain\Invoice\Invoice;
use Module\Client\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Invoice\ValueObject\InvoiceStatusEnum;
use Module\Client\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Domain\Order\ValueObject\OrderId;
use Module\Client\Domain\Shared\ValueObject\ClientId;
use Module\Client\Infrastructure\Models\Invoice as Model;
use Module\Shared\ValueObject\File;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $domainEventDispatcher,
    ) {
    }

    public function create(ClientId $clientId, OrderIdCollection $orders, File $document): Invoice
    {
        $invoice = DB::transaction(function () use ($clientId, $orders, $document) {
            $model = Model::create([
                'client_id' => $clientId->value(),
                'status' => InvoiceStatusEnum::NOT_PAID->value,
                'file' => $document->guid()
            ]);

            $insert = [];
            foreach ($orders as $orderId) {
                $insert[] = ['invoice_id' => $model->id, 'order_id' => $orderId->value()];
            }
            DB::table('client_invoice_orders')->insert($insert);

            return new Invoice(
                new InvoiceId($model->id),
                $clientId,
                InvoiceStatusEnum::NOT_PAID,
                $orders,
                $document,
            );
        });

        $this->domainEventDispatcher->dispatch(new InvoiceCreated($invoice));

        return $invoice;
    }

    public function find(InvoiceId $id): ?Invoice
    {
        return ($model = Model::find($id->value())) ? $this->fromModel($model) : null;
    }

    public function findByOrderId(OrderId $orderId): ?Invoice
    {
        $model = Model::whereHasOrderId($orderId->value())->first();

        return $model ? $this->fromModel($model) : null;
    }

    public function store(Invoice $invoice): void
    {
        $model = Model::find($invoice->id()->value());
        if (!$model) {
            throw new \Exception();
        }

        $model->status = $invoice->status()->value;
        $model->touch();
        $model->save();
    }

    private function fromModel(Model $model): Invoice
    {
        return new Invoice(
            new InvoiceId($model->id),
            new ClientId($model->client_id),
            InvoiceStatusEnum::from($model->status),
            new OrderIdCollection($model->orderIds()->map(fn($id) => new OrderId($id))),
            new File($model->document),
        );
    }
}