<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Factory;

use Module\Hotel\Moderation\Domain\Hotel\Hotel;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\Address;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\Contact;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\ContactCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\TimeSettings;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Sdk\Shared\Enum\ContactTypeEnum;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\ValueObject\Time;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;

    protected function fromArray(array $data): mixed
    {
        $contacts = $this->buildContacts($data['contacts']);

        $timeSettings = $data['time_settings'] ?? null;
        if (!empty($timeSettings)) {
            $timeSettings = TimeSettings::deserialize(
                json_decode($timeSettings, true)
            );
        }

        return new Hotel(
            id: new HotelId($data['id']),
            name: $data['name'],
            currency: CurrencyEnum::from($data['currency']),
            timeSettings: $timeSettings ?? $this->getDefaultTimeSettings(),
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

    private function getDefaultTimeSettings(): TimeSettings
    {
        $checkInTime = new Time('12:00');
        $checkOutTime = new Time('14:00');

        return new TimeSettings($checkInTime, $checkOutTime, null);
    }
}
