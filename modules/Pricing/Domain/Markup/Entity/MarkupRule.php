<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\Entity;

use Module\Pricing\Domain\Markup\ValueObject\MarkupGroupId;
use Module\Pricing\Domain\Markup\ValueObject\MarkupValue;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class MarkupRule extends AbstractAggregateRoot
{
    public function __construct(
        private readonly MarkupGroupId $groupId,
        //@todo может сделать hotelValue, hotelRoomValue, baseValue?
        private readonly ?int $hotelId,
        private readonly ?int $hotelRoomId,
        private MarkupValue $value
    ) {}

    public function groupId(): MarkupGroupId
    {
        return $this->groupId;
    }

    public function hotelId(): int
    {
        return $this->hotelId;
    }

    public function hotelRoomId(): int
    {
        return $this->hotelRoomId;
    }

    public function value(): MarkupValue
    {
        return $this->value;
    }

    public function setValue(MarkupValue $value): void
    {
        $this->value = $value;
    }
}
