<?php

namespace Module\Client\Invoicing\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Module\Client\Invoicing\Domain\Invoice\Invoice;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Invoicing\Infrastructure\Models\Invoice as Model;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Shared\ValueObject\File;
use Sdk\Shared\ValueObject\Timestamps;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function create(ClientId $clientId, OrderId $orderId, ?File $document): Invoice
    {
        return DB::transaction(function () use ($clientId, $orderId, $document) {
            $model = Model::create([
                'client_id' => $clientId->value(),
                'document' => $document?->guid(),
                'order_id' => $orderId->value()
            ]);

            return new Invoice(
                new InvoiceId($model->id),
                $clientId,
                $orderId,
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
        $model = Model::whereOrderId($orderId->value())->first();

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
        if ($invoice->isDeleted()) {
            $model->delete();

            return;
        }
        $model->save();
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
            new OrderId($model->order_id),
            $model->document ? new File($model->document) : null,
            new Timestamps(
                createdAt: \DateTimeImmutable::createFromInterface($model->created_at),
                updatedAt: \DateTimeImmutable::createFromInterface($model->updated_at),
            )
        );
    }
}
