<?php

namespace Module\Booking\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Booking\Hotel\Domain\Entity\Room;
use Module\Booking\Hotel\Domain\ValueObject\Price;
use Module\Integration\Traveline\Domain\Entity\ConfigInterface;

class RoomFactory extends AbstractEntityFactory
{
    protected string $entity = Room::class;

    public function __construct(private readonly ConfigInterface $config)
    {
        parent::__construct();
    }

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            $data['room_id'],
            app(GuestFactory::class)->createCollectionFrom($data['guests']),
            $data['rate_id'],
            new Price($data['price_net'], $this->config->getDefaultCurrency()),
            $data['check_in_condition']['start'] ?? null,
            $data['check_out_condition']['end'] ?? null,
            $data['note'],
        );
    }
}
