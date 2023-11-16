<?php

declare(strict_types=1);

namespace Module\Client\Payment\Infrastructure\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @method static Builder|Payment withPlantSum()
 *
 * @property int $id
 * @property int $client_id
 * @property PaymentStatusEnum $status
 * @property string $invoice_number
 * @property CurrencyEnum $payment_currency
 * @property float $payment_sum
 * @property int $payment_method_id
 * @property DateTime $payment_date
 * @property DateTime $issue_date
 * @property string $document_name
 * @property string $document
 * @property DateTime $created_at
 */
class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'client_payments';

    protected $fillable = [
        'client_id',
        'status',
        'invoice_number',
        'payment_currency',
        'payment_sum',
        'payment_method_id',
        'payment_date',
        'issue_date',
        'document_name',
        'document',
    ];

    protected $casts = [
        'client_id' => 'int',
        'status' => PaymentStatusEnum::class,
        'payment_currency' => CurrencyEnum::class,
        'payment_sum' => 'float',
        'payment_method_id' => 'int',
        'issue_date' => 'datetime:Y-m-d',
        'payment_date' => 'datetime:Y-m-d',
    ];

    public function scopeWithPlantSum(Builder $builder): void
    {
        $builder->addSelect(
            DB::raw('SELECT SUM(`sum`) FROM client_payment_plants WHERE payment_id=client_payments.id')
        );
    }
}
