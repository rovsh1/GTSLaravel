<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject;

use Illuminate\Support\Collection;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

/**
 * @extends Collection<int, Contact>
 */
class ContactCollection extends Collection implements ValueObjectInterface
{

}
