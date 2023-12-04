<?php

declare(strict_types=1);

namespace Module\Generic\Notification\Domain\Dto;

final class RecipientListDto
{
    /**
     * @param RecipientDto[] $recipients
     */
    public function __construct(public readonly array $recipients)
    {
    }

    public static function empty(): RecipientListDto
    {
        return new RecipientListDto([]);
    }

    public function isEmpty(): bool
    {
        return empty($this->recipients);
    }
}