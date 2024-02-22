<?php

namespace Pkg\IntegrationEventBus\Enums;

enum MessageStatusEnum: int
{
    case WAITING = 0;
    case FAILED = 1;
}