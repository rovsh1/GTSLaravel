<?php

declare(strict_types=1);

namespace Module\Shared\Application\Exception;

class BaseApplicationException extends \RuntimeException implements ApplicationExceptionInterface
{
    public function getHumanMessage(): string
    {
        return 'Неизвестная ошибка. Пожалуйста, обратитесь в техническую поддержку.';
    }
}
