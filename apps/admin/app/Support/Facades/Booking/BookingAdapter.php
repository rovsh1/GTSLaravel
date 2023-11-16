<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\ServiceTypeEnum;

/**
 * @method static array getStatuses()
 * @method static mixed getBooking(int $id)
 * @method static int createBooking(int $clientId, int|null $legalId, CurrencyEnum $currency, int $serviceId, int $managerId, int $creatorId, ?int $orderId, array|null $detailsData, string|null $note = null)
 * @method static int createHotelBooking(int $clientId, int|null $legalId, CurrencyEnum $currency, int $hotelId, int $managerId, int $creatorId, ?int $orderId, array|null $detailsData, string|null $note = null)
 * @method static array getAvailableActions(int $id)
 * @method static mixed updateStatus(int $id, int $status, string|null $notConfirmedReason = null, float|null $supplierPenalty = null, float|null $clientPenalty = null)
 * @method static array getStatusHistory(int $id)
 * @method static void updateNote(int $bookingId, string|null $note)
 * @method static BookingDto copyBooking(int $id)
 * @method static void deleteBooking(int $id)
 * @method static void bulkDeleteBookings(int[] $id)
 **/
class BookingAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\BookingAdapter::class;
    }
}
