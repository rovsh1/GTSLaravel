<?php

declare(strict_types=1);

namespace Module\Administrator\Application\Response;

use Module\Administrator\Domain\Entity\Administrator;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class AdministratorDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $presentation,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $post,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof Administrator);

        return new static(
            $entity->id()->value(),
            $entity->presentation(),
            $entity->email(),
            $entity->phone(),
            $entity->post(),
        );
    }
}
