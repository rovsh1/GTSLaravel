<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Enums\Hotel\StatusEnum;

class HotelStatus extends Enum
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct(StatusEnum::class, $name, $options);
    }
}
