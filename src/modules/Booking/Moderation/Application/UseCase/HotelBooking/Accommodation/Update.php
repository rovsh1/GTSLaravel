<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Moderation\Application\RequestDto\UpdateRoomRequestDto;
use Module\Booking\Moderation\Application\Service\AccommodationChecker;
use Module\Booking\Moderation\Application\Service\AccommodationFactory;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\Event\HotelBooking\AccommodationReplaced;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

final class Update implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly AccommodationFactory $accommodationFactory,
        private readonly AccommodationChecker $accommodationChecker,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(UpdateRoomRequestDto $requestDto): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($requestDto->bookingId));
        $currentAccommodation = $this->accommodationRepository->findOrFail(
            new AccommodationId($requestDto->accommodationId)
        );

        $this->accommodationChecker->validate(
            orderId: $booking->orderId()->value(),
            roomId: $requestDto->roomId,
            rateId: $requestDto->rateId,
            isResident: $requestDto->isResident,
            guestsCount: $currentAccommodation->guestIds()->count()
        );

        $this->accommodationFactory->fromRequest($requestDto);

        if ($currentAccommodation->roomInfo()->id() === $requestDto->roomId) {
            $this->doUpdate($booking, $currentAccommodation);
        } else {
            $this->doReplace($booking, $currentAccommodation);
        }

        $this->bookingUnitOfWork->touch($booking->id());
        $this->bookingUnitOfWork->commit();
    }

    private function doUpdate($booking, HotelAccommodation $currentAccommodation): void
    {
        $accommodationDetails = $this->accommodationFactory->buildDetails();
        if ($currentAccommodation->details()->isEqual($accommodationDetails)) {
            return;
        }

        $this->bookingUnitOfWork->persist($currentAccommodation);
        $currentAccommodation->updateDetails($accommodationDetails);
    }

    private function doReplace($booking, HotelAccommodation $beforeAccommodation): void
    {
        $this->bookingUnitOfWork->commiting(function () use ($booking, $beforeAccommodation) {
            $this->accommodationRepository->delete($beforeAccommodation->id());
            $newAccommodation = $this->accommodationFactory->create($booking->id());
            $maxGuestCount = $newAccommodation->roomInfo()->guestsCount();
            foreach ($beforeAccommodation->guestIds() as $index => $guestId) {
                if ($index + 1 > $maxGuestCount) {
                    break;
                }
                $newAccommodation->addGuest($guestId);
            }
            $this->accommodationRepository->store($newAccommodation);

            $this->eventDispatcher->dispatch(
                new AccommodationReplaced($newAccommodation, $beforeAccommodation)
            );
        });
    }
}
