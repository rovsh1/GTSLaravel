<?php

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Quotation\Application\Dto\BookingRoomDto;
use Module\Hotel\Quotation\Application\Exception\BookingQuotaException;
use Module\Hotel\Quotation\Application\RequestDto\BookRequestDto;
use Module\Hotel\Quotation\Application\RequestDto\ReserveRequestDto;
use Module\Hotel\Quotation\Application\Service\Factory\SupplierFactory;
use Module\Hotel\Quotation\Domain\ValueObject\BookingPeriod;

class QuotaBooker
{
    public function __construct(
        private readonly SupplierFactory $supplierFactory
    ) {}

    public function book(BookRequestDto $requestDto): void
    {
        $this->method(__FUNCTION__, $requestDto);
    }

    public function reserve(ReserveRequestDto $requestDto): void
    {
        $this->method(__FUNCTION__, $requestDto);
    }

    private function method(string $method, BookRequestDto|ReserveRequestDto $requestDto): void
    {
        DB::transaction(function () use ($method, $requestDto) {
            $booker = $this->supplierFactory->booker($requestDto->hotelId);

            $booker->cancelBooking($requestDto->bookingId);

            $this->ensureHasRoomQuota($booker, $requestDto->bookingRooms, $requestDto->bookingPeriod);

            foreach ($requestDto->bookingRooms as $roomDto) {
                $booker->$method(
                    $requestDto->bookingId,
                    $roomDto->roomId,
                    $requestDto->bookingPeriod,
                    $roomDto->count
                );
            }
        });
    }

    /**
     * @param BookingRoomDto[] $bookingRooms
     * @param BookingPeriod $bookingPeriod
     * @return void
     * @throws BookingQuotaException
     */
    private function ensureHasRoomQuota(
        SupplierQuotaBookerInterface $booker,
        array $bookingRooms,
        CarbonPeriod $bookingPeriod
    ): void {
        foreach ($bookingRooms as $roomDto) {
            $hasAvailable = $booker->hasAvailable($roomDto->roomId, $bookingPeriod, $roomDto->count);
            if (!$hasAvailable) {
                throw new BookingQuotaException();
//                throw new Exception("Room [$roomDto->roomId] quota not found");
            }
        }
    }
}
