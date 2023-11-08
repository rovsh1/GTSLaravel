<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\ChangeHistory;

enum AttributeTypeEnum
{
    case STRING;
    case INTEGER;
    case FLOAT;
    case BOOLEAN;
}