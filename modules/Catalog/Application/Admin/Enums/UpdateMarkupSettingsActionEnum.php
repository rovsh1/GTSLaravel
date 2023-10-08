<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\Enums;

enum UpdateMarkupSettingsActionEnum: string
{
    case UPDATE = 'update';
    case DELETE_FROM_COLLECTION = 'deleteFromCollection';
    case ADD_TO_COLLECTION = 'addToCollection';
}
