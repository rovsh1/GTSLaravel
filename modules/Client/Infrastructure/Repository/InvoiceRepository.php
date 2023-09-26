<?php

namespace Module\Client\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Module\Client\Domain\Invoice\Invoice;
use Module\Client\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Invoice\ValueObject\InvoiceStatusEnum;
use Module\Client\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Domain\Order\ValueObject\OrderId;
use Module\Client\Domain\Shared\ValueObject\ClientId;
use Module\Client\Infrastructure\Models\Invoice as Model;
use Module\Shared\ValueObject\File;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function create(ClientId $clientId, OrderIdCollection $orders, File $document): Invoice
    {
        return DB::transaction(function () use ($clientId, $orders, $document) {
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
                : InvoiceStatusEnum::from($model->status),
            new OrderIdCollection($model->orderIds()->map(fn($id) => new OrderId($id))),
            new File($model->document),
        );
    }
}