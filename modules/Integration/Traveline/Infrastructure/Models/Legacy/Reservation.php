<?php

namespace Module\Integration\Traveline\Infrastructure\Models\Legacy;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Hotel\Option;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Hotel\OptionTypeEnum;

/**
 * Module\Integration\Traveline\Infrastructure\Models\Reservation
 *
 * @property int $id
 * @property int|null $administrator_id
 * @property int|null $client_id
 * @property int|null $legal_id
 * @property int|null $manager_id
 * @property int|null $user_id
 * @property int $city_id
 * @property int $hotel_id
 * @property int $source
 * @property int $type
 * @property string|null $number
 * @property \Custom\Framework\Support\DateTime $date_checkin
 * @property \Custom\Framework\Support\DateTime $date_checkout
 * @property int $nights_number
 * @property \Module\Integration\Traveline\Infrastructure\Models\Legacy\ReservationStatusEnum $status
 * @property int $flag_request
 * @property int $flag_invoice
 * @property int $flag_voucher
 * @property string|null $note
 * @property int $deletion_mark
 * @property \Custom\Framework\Support\DateTime $created
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
 * @method static Builder|Reservation newModelQuery()
 * @method static Builder|Reservation newQuery()
 * @method static Builder|Reservation query()
 * @method static Builder|Reservation whereAdministratorId($value)
 * @method static Builder|Reservation whereChangedGross($value)
 * @method static Builder|Reservation whereChangedNet($value)
 * @method static Builder|Reservation whereCityId($value)
 * @method static Builder|Reservation whereClientId($value)
 * @method static Builder|Reservation whereCreated($value)
 * @method static Builder|Reservation whereDateChangedNet($value)
 * @method static Builder|Reservation whereDateCheckin($value)
 * @method static Builder|Reservation whereDateCheckout($value)
 * @method static Builder|Reservation whereDatePenaltyNet($value)
 * @method static Builder|Reservation whereDeletionMark($value)
 * @method static Builder|Reservation whereFlagInvoice($value)
 * @method static Builder|Reservation whereFlagRequest($value)
 * @method static Builder|Reservation whereFlagVoucher($value)
 * @method static Builder|Reservation whereHash($value)
 * @method static Builder|Reservation whereHotelId($value)
 * @method static Builder|Reservation whereId($value)
 * @method static Builder|Reservation whereLegalId($value)
 * @method static Builder|Reservation whereManagerId($value)
 * @method static Builder|Reservation whereNightsNumber($value)
 * @method static Builder|Reservation whereNote($value)
 * @method static Builder|Reservation whereNumber($value)
 * @method static Builder|Reservation wherePaymentCardType($value)
 * @method static Builder|Reservation wherePenaltyGross($value)
 * @method static Builder|Reservation wherePenaltyNet($value)
 * @method static Builder|Reservation whereSource($value)
 * @method static Builder|Reservation whereStatus($value)
 * @method static Builder|Reservation whereTotalGross($value)
 * @method static Builder|Reservation whereTotalNet($value)
 * @method static Builder|Reservation whereTotalNetHotel($value)
 * @method static Builder|Reservation whereType($value)
 * @method static Builder|Reservation whereUserId($value)
 * @method static Builder|Reservation withClient()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Option> $hotelOptions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Module\Integration\Traveline\Infrastructure\Models\Legacy\Room> $rooms
 * @property-read int $client_type
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

    public function scopeWithClient(Builder $builder)
    {
        $builder->addSelect("{$this->getTable()}.*");

        $clientsTable = with(new Client)->getTable();
        $builder->rightJoin(
            $clientsTable,
            function ($join) use ($clientsTable) {
                $join->on("{$clientsTable}.id", '=', "{$this->getTable()}.client_id");
            }
        )->addSelect([
            "{$clientsTable}.type as client_type",
            "{$clientsTable}.name as client_name",
        ]);
    }

    public function rooms()
    {
        return $this->hasMany(
            Room::class,
            'reservation_id',
            'id'
        );
    }

    public function hotelOptions()
    {
        return $this->hasMany(
            Option::class,
            'hotel_id',
            'hotel_id',
        );
    }

    public function hotelDefaultCheckInStart()
    {
        return $this->hasOne(
            Option::class,
            'hotel_id',
            'hotel_id',
        )->where('option', OptionTypeEnum::CHECKIN_START_PRESET);
    }

    public function hotelDefaultCheckOutEnd()
    {
        return $this->hasOne(
            Option::class,
            'hotel_id',
            'hotel_id',
        )->where('option', OptionTypeEnum::CHECKOUT_END_PRESET);
    }
}
