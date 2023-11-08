<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Factory;

use Module\Hotel\Moderation\Domain\Hotel\Hotel;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\Address;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\Contact;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\ContactCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\TimeSettings;
use Module\Shared\Contracts\Service\SerializerInterface;
use Module\Shared\Enum\ContactTypeEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;

    public function __construct(private readonly SerializerInterface $serializer)
    {
        parent::__construct();
    }

    protected function fromArray(array $data): mixed
    {
        $contacts = $this->buildContacts($data['contacts']);

        return new $this->entity(
            id: new HotelId($data['id']),
            name: $data['name'],
            currency: CurrencyEnum::from($data['currency']),
            timeSettings: $this->serializer->deserialize(TimeSettings::class, $data['time_settings']),
            address: new Address(
                country: $data['country_name'],
                city: $data['city_name'],
                address: $data['address'],
            ),
            contacts: $contacts
        );
    }

    private function buildContacts(array $contacts): ContactCollection
    {
        $preparedContacts = array_map(fn(array $contactData) => new Contact(
            type: ContactTypeEnum::from($contactData['type']),
            value: $contactData['value'],
            isMain: $contactData['is_main']
        ), $contacts);

        return new ContactCollection($preparedContacts);
    }
}
