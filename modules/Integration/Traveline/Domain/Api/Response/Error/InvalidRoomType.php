<?php

namespace Module\Integration\Traveline\Domain\Api\Response\Error;

class InvalidRoomType extends AbstractTravelineError
{
    public function __construct()
    {
        parent::__construct(
            730,
            'Invalid room type'
        );
    }
}
