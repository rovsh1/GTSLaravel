<?php

namespace Module\Hotel\Quotation\Application\Service;

use Illuminate\Support\Facades\DB;
use Module\Hotel\Quotation\Application\Dto\BookingRoomDto;
use Module\Hotel\Quotation\Application\Exception\BookingQuotaException;
use Module\Hotel\Quotation\Application\RequestDto\BookRequestDto;
use Module\Hotel\Quotation\Application\RequestDto\ReserveRequestDto;
use Module\Hotel\Quotation\Domain\Repository\QuotaRepositoryInterface;
use Module\Hotel\Quotation\Domain\ValueObject\BookingId;
use Module\Hotel\Quotation\Domain\ValueObject\BookingPeriod;
use Module\Hotel\Quotation\Domain\ValueObject\RoomId;
use Module\Shared\Exception\ApplicationException;

class QuotaBooker
{
    public function __construct(
        private readonly QuotaRepositoryInterface $quotaRepository
    ) {
    }

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
        $bookingId = new BookingId($requestDto->bookingId);
        $bookingPeriod = new BookingPeriod(
            \DateTimeImmutable::createFromInterface($requestDto->bookingPeriod->getStartDate()),
            \DateTimeImmutable::createFromInterface($requestDto->bookingPeriod->getEndDate()),
        );

        DB::transaction(function () use ($method, $requestDto, $bookingId, $bookingPeriod) {
            $this->quotaRepository->cancelBooking($bookingId);

            $this->ensureHasRoomQuota($requestDto->bookingRooms, $bookingPeriod);

            foreach ($requestDto->bookingRooms as $roomDto) {
                $this->quotaRepository->$method(
                    $bookingId,
                    new RoomId($roomDto->roomId),
                    $bookingPeriod,
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
    private function ensureHasRoomQuota(array $bookingRooms, BookingPeriod $bookingPeriod): void
    {
        foreach ($bookingRooms as $roomDto) {
            if (!$this->quotaRepository->hasAvailable(
                new RoomId($roomDto->roomId),
                $bookingPeriod,
                $roomDto->count
            )) {
                throw new BookingQuotaException(ApplicationException::BOOKING_NOT_FOUND_ROOM_DATE_QUOTA);
//                throw new Exception("Room [$roomDto->roomId] quota not found");
            }
        }
    }
}