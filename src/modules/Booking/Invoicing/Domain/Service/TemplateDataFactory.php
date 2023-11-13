<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service;

use Module\Booking\Invoicing\Domain\Invoice\Invoice;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\BookingPeriodDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\CarDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\CarPriceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\GuestDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\PriceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\RoomDto;
use Module\Booking\Invoicing\Domain\Service\Dto\BookingDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ClientDto;
use Module\Booking\Invoicing\Domain\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Invoicing\Domain\Service\Dto\InvoiceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ManagerDto;
use Module\Booking\Invoicing\Domain\Service\Dto\OrderDto;
use Module\Booking\Invoicing\Domain\ValueObject\InvoiceId;
use Module\Booking\Invoicing\Domain\ValueObject\OrderIdCollection;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking as DetailsEntity;
use Module\Booking\Shared\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Shared\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\DetailOptionsDataFactory;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod as HotelBookingPeriod;
use Module\Booking\Shared\Domain\Guest\Guest;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Service\OrderStatusStorageInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Contracts\Adapter\CountryAdapterInterface;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;
use Module\Shared\Enum\GenderEnum;

class TemplateDataFactory
{
    private array $countryNamesIndexedId;

    public function __construct(
        CountryAdapterInterface $countryAdapter,
        private readonly CompanyRequisitesInterface $companyRequisites,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly OrderStatusStorageInterface $orderStatusStorage,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DetailOptionsDataFactory $detailOptionsDataFactory,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
    ) {
        $countries = $countryAdapter->get();
        $this->countryNamesIndexedId = collect($countries)->keyBy('id')->map->name->all();
    }

    public function build(
        InvoiceId $invoiceId,
        OrderIdCollection $orderIds,
        ClientId $clientId,
        \DateTimeInterface $createdAt
    ): array {
        $orderId = null;
        foreach ($orderIds as $oId) {
            $orderId = $oId;
            break;
        }
        $order = $this->orderRepository->findOrFail($orderId);

        return [
            'order' => $this->buildOrderDto($order),
            'bookings' => $this->buildBookings($orderId),
            'company' => $this->getCompanyRequisites(),
            'manager' => $this->buildOrderManagerDto($order),
            'client' => $this->buildClientDto($clientId),
            'invoice' => $this->buildInvoiceDto($invoiceId, $createdAt),
        ];
    }

    private function buildInvoiceDto(InvoiceId $id, \DateTimeInterface $createdAt): InvoiceDto
    {
        return new InvoiceDto(
            (string)$id->value(),
            $createdAt->format('d.m.Y H:i')
        );
    }

    /**
     * @param OrderId $orderId
     * @return BookingDto[]
     */
    private function buildBookings(OrderId $orderId): array
    {
        $bookings = $this->bookingRepository->getByOrderId($orderId);

        return array_map(fn(Booking $booking) => $this->buildBooking($booking), $bookings);
    }

    private function buildBooking(Booking $booking): BookingDto
    {
        $clientPrice = $booking->prices()->clientPrice();
        $price = new PriceDto(
            $clientPrice->manualValue() ?? $clientPrice->calculatedValue(),
            $clientPrice->currency()->name
        );

        $detailsRepository = $this->detailsRepositoryFactory->build($booking);
        /** @var ServiceDetailsInterface $details */
        $details = $detailsRepository->findOrFail($booking->id());
        try {
            $detailOptions = $this->detailOptionsDataFactory->build($details);
        } catch (\Throwable $e) {
            $detailOptions = collect();
        }
        $bookingPeriod = null;
        if (method_exists($details, 'bookingPeriod')) {
            $bookingPeriod = $this->buildBookingPeriod($details->bookingPeriod());
        }

        $rooms = null;
        if ($details instanceof DetailsEntity) {
            $serviceInfo = "Отель: {$details->hotelInfo()->name()}";
            $accommodations = $this->accommodationRepository->getByBookingId($booking->id());
            $rooms = $this->buildRoomsDto($accommodations, $details);
        } else {
            $serviceInfo = $details->serviceInfo()->title();
        }

        $cars = null;
        if (method_exists($details, 'carBids')) {
            $cars = $this->buildCars(
                $details->carBids(),
                $details->serviceInfo()->supplierId(),
                $bookingPeriod?->countDays
            );
        }

        $guests = null;
        if (method_exists($details, 'guestIds')) {
            $guests = $this->buildGuests($details->guestIds());
        }

        return new BookingDto(
            (string)$booking->id()->value(),
            $serviceInfo,
            $bookingPeriod,
            $detailOptions,
            $price,
            null,
            $rooms,
            $cars,
            $guests
        );
    }

    /**
     * @param AccommodationCollection $accommodations
     * @param DetailsEntity $bookingDetails
     * @return RoomDto[]
     */
    private function buildRoomsDto(AccommodationCollection $accommodations, DetailsEntity $bookingDetails): array
    {
        if ($accommodations->count() === 0) {
            return [];
        }
        $hotelPriceRates = $this->hotelAdapter->getHotelRates($bookingDetails->hotelInfo()->id());
        $hotelPriceRatesIndexedId = collect($hotelPriceRates)->keyBy('id');

        return $accommodations->map(
            function (HotelAccommodation $accommodation) use ($bookingDetails, $hotelPriceRatesIndexedId) {
                $checkInTime = $bookingDetails->hotelInfo()->checkInTime()->value();
                if ($accommodation->details()->earlyCheckIn() !== null) {
                    $checkInTime = $accommodation->details()->earlyCheckIn()->timePeriod()->from();
                }
                $checkOutTime = $bookingDetails->hotelInfo()->checkOutTime()->value();
                if ($accommodation->details()->lateCheckOut() !== null) {
                    $checkOutTime = $accommodation->details()->lateCheckOut()->timePeriod()->to();
                }

                return new RoomDto(
                    $accommodation->roomInfo()->name(),
                    $hotelPriceRatesIndexedId[$accommodation->details()->rateId()]->name,
                    $checkInTime,
                    $checkOutTime,
                    $this->buildGuests($accommodation->guestIds()),
                    $accommodation->details()->guestNote(),
                );
            }
        );
    }

    private function buildClientDto(ClientId $clientId): ClientDto
    {
        $client = $this->clientAdapter->find($clientId->value());

        return new ClientDto(
            $client->name,
            '{phone}',
            '{address}'
        );
    }

    /**
     * @param GuestIdCollection $guestIds
     * @return GuestDto[]
     */
    private function buildGuests(GuestIdCollection $guestIds): array
    {
        if ($guestIds->count() === 0) {
            return [];
        }
        $guests = $this->guestRepository->get($guestIds);

        return collect($guests)->map(fn(Guest $guest) => new GuestDto(
            $guest->fullName(),
            $guest->gender() === GenderEnum::MALE ? 'Мужской' : 'Женский',
            $this->countryNamesIndexedId[$guest->countryId()]
        ))->all();
    }

    private function buildCars(CarBidCollection $carBids, int $supplierId, ?int $daysCount): array
    {
        $cars = $this->supplierAdapter->getSupplierCars($supplierId);
        $carsIndexedById = collect($cars)->keyBy('id');

        return $carBids->map(fn(CarBid $carBid) => new CarDto(
            $carsIndexedById[$carBid->carId()->value()]->mark,
            $carsIndexedById[$carBid->carId()->value()]->model,
            $carBid->carsCount(),
            new CarPriceDto(
                $carBid->prices()->clientPrice()->valuePerCar(),
                $carBid->clientPriceValue(),
                $daysCount === null
                    ? $carBid->clientPriceValue()
                    : $carBid->clientPriceValue() * $daysCount
            )
        ));
    }

    private function buildOrderDto(Order $order): OrderDto
    {
        $statusName = $this->orderStatusStorage->getName($order->status());

        return new OrderDto(
            (string)$order->id()->value(),
            $statusName,
            $order->currency()->name
        );
    }

    private function buildOrderManagerDto(Order $order): ManagerDto
    {
        $managerDto = $this->administratorAdapter->getOrderAdministrator($order->id());

        return new ManagerDto(
            $managerDto->name ?? $managerDto?->presentation,
            $managerDto?->email,
            $managerDto?->phone,
        );
    }

    private function buildBookingPeriod(BookingPeriod|HotelBookingPeriod $bookingPeriod): BookingPeriodDto
    {
        if (method_exists($bookingPeriod, 'nightsCount')) {
            $daysCount = $bookingPeriod->nightsCount();
        } else {
            $daysCount = $bookingPeriod->daysCount();
        }

        return new BookingPeriodDto(
            $bookingPeriod->dateFrom()->format('d.m.Y'),
            $bookingPeriod->dateFrom()->format('H:i'),
            $bookingPeriod->dateTo()->format('d.m.Y'),
            $bookingPeriod->dateTo()->format('H:i'),
            $daysCount,
        );
    }

    private function getCompanyRequisites(): CompanyRequisitesDto
    {
        return new CompanyRequisitesDto(
            name: $this->companyRequisites->name(),
            phone: $this->companyRequisites->phone(),
            email: $this->companyRequisites->email(),
            legalAddress: $this->companyRequisites->legalAddress(),
            signer: $this->companyRequisites->signer(),
            region: $this->companyRequisites->region(),
        );
    }
}
