<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Model\Invoice;

use DateTime;
use Module\Booking\Infrastructure\Enum\InvoicePaymentTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int id
 * @property int invoice_id
 * @property int booking_id
 * @property InvoicePaymentTypeEnum type
 * @property string number
 * @property float payment_sum
 * @property int payment_method
 * @property string document_name
 * @property string document
 * @property DateTime issued_at
 * @property DateTime paid_at
 * @property DateTime created_at
 */
class Payment extends Model
{
    const UPDATED_AT = null;

    protected $table = 'order_invoice_payments';

    protected $fillable = [
        'invoice_id',
        'booking_id',
        'type',
        'number',
        'payment_sum',
        'payment_method',
        'document_name',
        'document',
        'issued_at',
        'paid_at',
    ];

    protected $casts = [
        'invoice_id' => 'int',
        'booking_id' => 'int',
        'type' => InvoicePaymentTypeEnum::class,
        'payment_sum' => 'float',
        'payment_method' => 'int',
//        'document' => 'int',
        'issued_at' => 'datetime:Y-m-d H:i:s',
        'paid_at' => 'datetime:Y-m-d H:i:s',
    ];
}
