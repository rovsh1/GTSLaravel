<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Module\Reservation\HotelReservation\Infrastructure\Models\Reservation
 *
 * @property int $id
 * @property int|null $administrator_id
 * @property int|null $client_id
 * @property-read int|null $client_type
 * @property int|null $legal_id
 * @property int|null $manager_id
 * @property int|null $user_id
 * @property int $city_id
 * @property int $hotel_id
 * @property int $source
 * @property int $type
 * @property string|null $number
 * @property \Illuminate\Support\Carbon $date_checkin
 * @property \Illuminate\Support\Carbon $date_checkout
 * @property int $nights_number
 * @property int $status
 * @property int $flag_request
 * @property int $flag_invoice
 * @property int $flag_voucher
 * @property string|null $note
 * @property int $deletion_mark
 * @property \Illuminate\Support\Carbon $created
 * @property string|null $penalty_gross
 * @property string|null $penalty_net
 * @property string|null $date_penalty_net
 * @property string|null $changed_gross
 * @property string|null $changed_net
 * @property string|null $date_changed_net
 * @property string|null $total_gross
 * @property string|null $total_net
 * @property string|null $total_net_hotel
 * @property string|null $hash
 * @property int|null $payment_card_type
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereAdministratorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereChangedGross($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereChangedNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDateChangedNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDateCheckin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDateCheckout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDatePenaltyNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDeletionMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereFlagInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereFlagRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereFlagVoucher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereLegalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereNightsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePaymentCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePenaltyGross($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePenaltyNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereTotalGross($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereTotalNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereTotalNetHotel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation withClientType()
 * @mixin \Eloquent
 */
class Reservation extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = null;

    protected $table = 'reservation';

    protected $fillable = [
        'administrator_id',
        'client_id',
        'legal_id',
        'manager_id',
        'user_id',
        'city_id',
        'hotel_id',
        'source',
        'type',
        'number',
        'date_checkin',
        'date_checkout',
        'nights_number',
        'status',
        'flag_request',
        'flag_invoice',
        'flag_voucher',
        'note',
        'deletion_mark',
        'created',
        'penalty_gross',
        'penalty_net',
        'date_penalty_net',
        'changed_gross',
        'changed_net',
        'date_changed_net',
        'total_gross',
        'total_net',
        'total_net_hotel',
        'hash',
        'payment_card_type',
    ];

    protected $casts = [
        'date_checkin' => 'date',
        'date_checkout' => 'date',
        'status' => ReservationStatusEnum::class,
    ];

    public function scopeWithClientType(Builder $builder)
    {
        $builder->addSelect("{$this->getTable()}.*");

        $clientsTable = with(new Client)->getTable();
        $builder->rightJoin(
            $clientsTable,
            function ($join) use ($clientsTable) {
                $join->on("{$clientsTable}.id", '=', "{$this->getTable()}.client_id");
            }
        )->addSelect("{$clientsTable}.type as client_type");
    }
}
