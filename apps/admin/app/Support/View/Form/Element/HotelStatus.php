<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Hotel\StatusEnum;

class HotelStatus extends EnumSelect
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct(StatusEnum::class, $name, $options);
    }
}
