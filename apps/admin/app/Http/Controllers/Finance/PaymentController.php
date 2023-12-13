<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Finance;

use App\Admin\Models\Reference\PaymentMethod;
use App\Admin\Support\Facades\Finance\PaymentAdapter;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Http\JsonResponse;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum;
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
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->id('id', ['text' => '№', 'order' => true])
            ->text('client_name', ['text' => 'Клиент', 'order' => true])
            ->enum('status', ['text' => 'Статус', 'enum' => PaymentStatusEnum::class])
            ->text('order_ids', [
                'text' => 'Заказы',
                'renderer' => fn($row) => "<a href='#' class='js-order-pay-link' data-payment-id='{$row->id}'>Распределить</a>"
            ])
            ->date('issue_date', ['text' => 'Дата выставления', 'order' => true])
            ->date('payment_date', ['text' => 'Дата оплаты', 'order' => true])
            ->price('payment_sum', ['text' => 'Сумма', 'currencyIndex' => 'payment_currency'])
            ->price('lend_sum', ['text' => 'Распределено', 'currencyIndex' => 'payment_currency'])
            ->price('remaining_sum', ['text' => 'Остаток', 'currencyIndex' => 'payment_currency'])
            ->file('file', ['text' => 'Файл'])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->orderBy('id', 'desc');
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->setOption('enctype', 'multipart/form-data')
            ->client('client_id', ['label' => 'Клиент', 'required' => true, 'emptyItem' => '', 'onlyWithOrders' => true])
            ->text('invoice_number', ['label' => 'Номер счет-фактуры', 'required' => true])
            ->date('issue_date', ['label' => 'Дата выставления', 'required' => true])
            ->date('payment_date', ['label' => 'Дата оплаты', 'required' => true])
            ->number('payment_sum', ['label' => 'Сумма оплаты', 'required' => true])
            ->currency('payment_currency', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
            ->select(
                'payment_method_id',
                ['label' => 'Способ оплаты', 'required' => true, 'items' => PaymentMethod::get(), 'emptyItem' => '']
            )
            ->text('document_name', ['label' => 'Документ'])
            ->file('file', ['label' => 'Файл']);
    }
}
