<?php

namespace Module\Client\Invoicing\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Module\Client\Invoicing\Domain\Invoice\Invoice;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceStatusEnum;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Invoicing\Infrastructure\Models\Invoice as Model;
use Module\Client\Invoicing\Infrastructure\Models\Order;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\ValueObject\File;
use Module\Shared\ValueObject\Timestamps;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function create(ClientId $clientId, OrderIdCollection $orders, ?File $document): Invoice
    {
        return DB::transaction(function () use ($clientId, $orders, $document) {
            $model = Model::create([
                'client_id' => $clientId->value(),
                'status' => InvoiceStatusEnum::NOT_PAID,
                'document' => $document?->guid()
            ]);

            $orderIds = $orders->map(fn(OrderId $id) => $id->value());
            Order::whereIn('id', $orderIds)->update(['invoice_id' => $model->id]);

            return new Invoice(
                new InvoiceId($model->id),
                $clientId,
                InvoiceStatusEnum::NOT_PAID,
                $orders,
                $document,
                new Timestamps(
                    \DateTimeImmutable::createFromInterface($model->created_at),
                    \DateTimeImmutable::createFromInterface($model->updated_at),
                )
            );
        });
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
        if ($model->document === null && $invoice->document() !== null) {
            $model->document = $invoice->document()->guid();
        }

        $model->touch();
        if ($invoice->status() === InvoiceStatusEnum::DELETED) {
            $model->delete();
        } else {
            $model->status = $invoice->status()->value;
            $model->save();
        }
    }

    public function query(): Builder
    {
        return Model::query();
    }

    private function fromModel(Model $model): Invoice
    {
        return new Invoice(
            new InvoiceId($model->id),
            new ClientId($model->client_id),
            $model->trashed()
                ? InvoiceStatusEnum::DELETED
                : $model->status,
            new OrderIdCollection($model->orderIds()->map(fn($id) => new OrderId($id))),
            $model->document ? new File($model->document) : null,
            new Timestamps(
                createdAt: \DateTimeImmutable::createFromInterface($model->created_at),
                updatedAt: \DateTimeImmutable::createFromInterface($model->updated_at),
            )
        );
    }
}
