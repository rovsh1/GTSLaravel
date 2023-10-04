<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Builder getBookingQuery()
 * @method static array getStatuses()
 * @method static mixed getBooking(int $id)
 * @method static int createBooking(int $cityId, int $clientId, int|null $legalId, int $currencyId, int $serviceId, int $creatorId, ?int $orderId, ?string $note = null)
 * @method static array getAvailableActions(int $id)
 * @method static mixed updateStatus(int $id, int $status, string|null $notConfirmedReason = null, float|null $cancelFeeAmount = null)
 * @method static array getStatusHistory(int $id)
 * @method static void updateNote(int $bookingId, string|null $note)
 * @method static int copyBooking(int $id)
 * @method static void deleteBooking(int $id)
 * @method static void bulkDeleteBookings(int[] $id)
 **/
class TransferAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\TransferAdapter::class;
    }
}
