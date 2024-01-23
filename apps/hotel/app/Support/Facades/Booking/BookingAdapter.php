<?php

declare(strict_types=1);

namespace App\Hotel\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Sdk\Shared\Enum\CurrencyEnum;

/**
 * @method static array getStatuses()
 * @method static mixed getBooking(int $id)
 * @method static array getAvailableActions(int $id)
 * @method static mixed updateStatus(int $id, int $status, string|null $notConfirmedReason = null, float|null $supplierPenalty = null, float|null $clientPenalty = null)
 * @method static mixed setNoCheckIn(int $id, float|null $supplierPenalty = null)
 * @method static array getStatusHistory(int $id)
 * @method static void updateNote(int $bookingId, string|null $note)
 **/
class BookingAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Hotel\Support\Adapters\Booking\BookingAdapter::class;
    }
}
