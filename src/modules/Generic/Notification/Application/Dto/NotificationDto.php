<?php

namespace Module\Generic\Notification\Application\Dto;

class NotificationDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
    ) {
    }
}