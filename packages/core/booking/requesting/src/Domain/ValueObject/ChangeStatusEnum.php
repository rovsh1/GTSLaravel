<?php

namespace Pkg\Booking\Requesting\Domain\ValueObject;

enum ChangeStatusEnum
{
    case CREATED;
    case UPDATED;
    case DELETED;
}
