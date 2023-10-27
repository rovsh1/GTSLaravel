<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\UseCase;

use Module\Catalog\Application\Admin\ResponseDto\PriceRateDto;
use Module\Catalog\Domain\Hotel\Entity\PriceRate;
use Module\Catalog\Domain\Hotel\Repository\PriceRateRepositoryInterface;
use Module\Catalog\Domain\Hotel\ValueObject\HotelId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetPriceRates implements UseCaseInterface
{
    public function __construct(
        private readonly PriceRateRepositoryInterface $repository,
    ) {}

    /**
     * @param int $hotelId
     * @return PriceRateDto[]
     */
    public function execute(int $hotelId): array
    {
        $priceRates = $this->repository->get(new HotelId($hotelId));

        return array_map(fn(PriceRate $priceRate) => new PriceRateDto(
            $priceRate->id,
            $priceRate->name,
            $priceRate->description,
        ), $priceRates);
    }
}
