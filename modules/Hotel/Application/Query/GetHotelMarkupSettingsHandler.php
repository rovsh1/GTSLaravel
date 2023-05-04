<?php

namespace Module\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Dto\MarkupSettings\MarkupSettingsDto;
use Module\Hotel\Infrastructure\Repository\MarkupSettingsRepository;

class GetHotelMarkupSettingsHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly MarkupSettingsRepository $repository
    ) {}

    public function handle(QueryInterface|GetHotelMarkupSettings $query): MarkupSettingsDto
    {
        $markup = $this->repository->get($query->hotelId);

        return MarkupSettingsDto::from($markup);
    }
}
