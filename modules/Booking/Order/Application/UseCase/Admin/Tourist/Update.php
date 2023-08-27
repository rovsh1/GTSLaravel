<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin\Tourist;

use Module\Booking\Order\Application\Request\UpdateTouristDto;
use Module\Booking\Order\Domain\Repository\TouristRepositoryInterface;
use Module\Booking\Order\Domain\ValueObject\TouristId;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly TouristRepositoryInterface $touristRepository,
    ) {}

    public function execute(UpdateTouristDto $request): bool
    {
        $tourist = $this->touristRepository->find(new TouristId($request->touristId));
        if ($tourist === null) {
            throw new EntityNotFoundException('Tourist not found');
        }
        if ($tourist->fullName() !== $request->fullName) {
            $tourist->setFullName($request->fullName);
        }
        if ($tourist->countryId() !== $request->countryId) {
            $tourist->setCountryId($request->countryId);
        }
        $newGender = GenderEnum::from($request->gender);
        if ($tourist->gender() !== $newGender) {
            $tourist->setGender($newGender);
        }
        if ($tourist->isAdult() !== $request->isAdult) {
            $tourist->setIsAdult($request->isAdult);
        }
        if ($tourist->age() !== $request->age) {
            $tourist->setAge($request->age);
        }

        return $this->touristRepository->store($tourist);
    }
}
