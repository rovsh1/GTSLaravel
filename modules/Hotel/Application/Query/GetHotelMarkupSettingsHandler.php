<?php

namespace Module\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Dto\MarkupSettingsDto;
use Module\Hotel\Infrastructure\Repository\MarkupRepository;

class GetHotelMarkupSettingsHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly MarkupRepository $repository
    ) {}

    public function handle(QueryInterface|GetHotelMarkupSettings $query): MarkupSettingsDto
    {
        $markup = $this->repository->get($query->hotelId);

        return MarkupSettingsDto::from($markup);
    }
}
