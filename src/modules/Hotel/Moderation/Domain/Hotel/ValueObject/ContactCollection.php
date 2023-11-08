<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject;

use Illuminate\Support\Collection;
use Module\Shared\Contracts\Domain\ValueObjectInterface;

/**
 * @extends Collection<int, Contact>
 */
class ContactCollection extends Collection implements ValueObjectInterface
{

}
