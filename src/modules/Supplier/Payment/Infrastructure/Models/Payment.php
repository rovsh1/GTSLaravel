<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Infrastructure\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\PaymentStatusEnum;

/**
 * @method static Builder|Payment withLandings()
 * @method static Builder|Payment whereBookingId(int $bookingId)
 *
 * @property int $id
 * @property int $supplier_id
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
 * @property-read Collection<int, Landing> $landings
 */
class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'supplier_payments';

    protected $fillable = [
        'supplier_id',
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
        'supplier_id' => 'int',
        'status' => PaymentStatusEnum::class,
        'payment_currency' => CurrencyEnum::class,
        'payment_sum' => 'float',
        'payment_method_id' => 'int',
        'issue_date' => 'datetime:Y-m-d',
        'payment_date' => 'datetime:Y-m-d',
    ];

    public function scopeWithLandings(Builder $builder): void
    {
        $builder->with(['landings']);
    }

    public function scopeWhereBookingId(Builder $builder, int $bookingId): void
    {
        $builder->whereHas('landings', function (Builder $query) use ($bookingId) {
            $query->where('booking_id', $bookingId);
        });
    }

    public function landings(): HasMany
    {
        return $this->hasMany(Landing::class, 'payment_id');
    }
}
