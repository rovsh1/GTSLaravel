<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Models\Reference\PaymentMethod;
use App\Admin\Support\Facades\Client\PaymentAdapter;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Http\JsonResponse;
use Sdk\Shared\Enum\PaymentStatusEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'payment';
    }

    public function get(int $id): JsonResponse
    {
        $payment = PaymentAdapter::get($id);
        if ($payment === null) {
            throw new NotFoundHttpException('Payment not found');
        }

        return response()->json($payment);
    }

    protected function gridFactory(): GridContract
    {
        $paymentMethods = PaymentMethod::get()->keyBy('id')->map->name;

        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->id('id', ['text' => '№', 'order' => true])
            ->text('client_name', ['text' => 'Клиент', 'order' => true])
            ->text('invoice_number', ['text' => '№ счет-фактуры'])
            ->enum('status', [
                'text' => 'Статус',
                'enum' => PaymentStatusEnum::class,
                'renderer' => fn($r, $v) => '<span class="payment-status-' . strtolower($r->status->name) . '">'
                    . $v . '</span>'
            ])
            ->text('order_ids', [
                'text' => '',
                'renderer' => fn($row) => '<a href="#" class="icon js-order-pay-link"'
                    . ' title="Распределить" data-payment-id="' . $row->id . '">assignment_add</a>'
            ])
            ->date('issue_date', ['text' => 'Дата выст.', 'order' => true])
            ->date('payment_date', ['text' => 'Дата опл.', 'order' => true])
            ->price('payment_sum', ['text' => 'Сумма', 'currencyIndex' => 'payment_currency'])
            ->text('lend_sum', [
                'text' => 'Распред. (Ост.)',
                'currencyIndex' => 'payment_currency',
                'renderer' => function ($r) {
                    $cls = match (true) {
                        $r->lend_sum <= 0 => 'payment-status-not_paid',
                        $r->lend_sum < $r->payment_sum => 'payment-status-partial_paid',
                        $r->lend_sum == $r->payment_sum => 'payment-status-paid',
                        default => ''
                    };

                    return '<span class="' . $cls . '">' . Format::price($r->lend_sum) . '</span>'
                        . ' (' . Format::price($r->remaining_sum) . ')';
                }
            ])
            ->text(
                'payment_method_id',
                ['text' => 'Способ', 'renderer' => fn($r, $v) => $paymentMethods[$v] ?? '']
            )
//            ->text('document_name', ['text' => 'Документ'])
            ->file('file', [
                'text' => 'Файл',
                'renderer' => function ($r) {
                    return $r->file
                        ? '<a href="' . $r->file->url . '" title="' . $r->document_name . '" target="_blank">'
                        . ($r->document_name ?? $r->file->name) . '</a>'
                        : '';
                }
            ])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->orderBy('id', 'desc');
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->setOption('enctype', 'multipart/form-data')
            ->client('client_id', [
                    'label' => 'Клиент',
                    'required' => true,
                    'emptyItem' => '',
                    'onlyWithOrders' => true
                ]
            )
            ->text('invoice_number', ['label' => 'Номер счет-фактуры', 'required' => true])
            ->date('issue_date', ['label' => 'Дата выставления', 'required' => true])
            ->date('payment_date', ['label' => 'Дата оплаты', 'required' => true])
            ->number('payment_sum', ['label' => 'Сумма оплаты', 'required' => true])
            ->hidden('payment_currency', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
            ->select(
                'payment_method_id',
                ['label' => 'Способ оплаты', 'required' => true, 'items' => PaymentMethod::get(), 'emptyItem' => '']
            )
            ->text('document_name', ['label' => 'Документ'])
            ->file('file', ['label' => 'Файл']);
    }
}
