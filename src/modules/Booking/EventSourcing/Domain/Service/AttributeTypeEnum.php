<?php

namespace Module\Booking\EventSourcing\Domain\Service;

enum AttributeTypeEnum
{
    case STRING;
    case INTEGER;
    case FLOAT;
    case BOOLEAN;
    case DATE;
}