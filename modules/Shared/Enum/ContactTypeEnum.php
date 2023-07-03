<?php

namespace Module\Shared\Enum;

enum ContactTypeEnum: int
{
    case PHONE = 1;
    case EMAIL = 2;
//	const FAX = 3;
    case ADDRESS = 4;
    case SITE = 5;

    public function key(): string
    {
        return strtolower($this->name);
    }

    public function title(): string
    {
        return __('enum.' . strtolower($this->name));
    }
}
