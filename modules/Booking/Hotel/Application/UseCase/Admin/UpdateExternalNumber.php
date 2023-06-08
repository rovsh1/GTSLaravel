<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Hotel\Domain\Repository\DetailsRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo\ExternalNumber;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo\ExternalNumberTypeEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateExternalNumber implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $type, ?string $number): void
    {
        $typeEnum = ExternalNumberTypeEnum::from($type);
        $details = $this->repository->find($id);
        $externalNumber = new ExternalNumber($typeEnum, $number);
        if ($details->additionalInfo() === null) {
            $additionalInfo = new AdditionalInfo(null);
            $details->setAdditionalInfo($additionalInfo);
        }
        $details->additionalInfo()->setExternalNumber($externalNumber);
        $this->repository->update($details);
    }
}
