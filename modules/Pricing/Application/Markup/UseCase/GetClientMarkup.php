<?php

declare(strict_types=1);

namespace Module\Pricing\Application\Markup\UseCase;

use Module\Pricing\Application\Markup\Response\MarkupValueDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetClientMarkup implements UseCaseInterface
{
    public function execute(int $groupId, int $hotelId, ?int $hotelRoomId = null): MarkupValueDto
    {
        if ($hotelRoomId !== null) {
            return MarkupValueDto::fromDomain();
        }

        //2. Ищу по отелю
        $markupValue = 123;
        if ($markupValue !== null) {
            return MarkupValueDto::fromDomain();
        }

        //3. Ищу по группе
        return MarkupValueDto::fromDomain($markupValue);
    }
}
