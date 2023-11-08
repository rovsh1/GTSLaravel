<?php

namespace Module\Hotel\Moderation\Application\Admin\Query;

use Module\Hotel\Moderation\Application\Admin\Response\MarkupSettingsDto;
use Module\Hotel\Moderation\Infrastructure\Repository\MarkupSettingsRepository;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetHotelMarkupSettingsHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly MarkupSettingsRepository $repository
    ) {}

    public function handle(QueryInterface|GetHotelMarkupSettings $query): MarkupSettingsDto
    {
        $markup = $this->repository->get($query->hotelId);

        return MarkupSettingsDto::fromDomain($markup);
    }
}
