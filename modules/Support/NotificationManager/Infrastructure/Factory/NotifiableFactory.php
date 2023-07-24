<?php

namespace Module\Support\NotificationManager\Infrastructure\Factory;

use Module\Services\NotificationManager\Infrastructure\Factory\AdministratorModel;
use Module\Services\NotificationManager\Infrastructure\Factory\ClientContactModel;
use Module\Services\NotificationManager\Infrastructure\Factory\HotelAdministratorModel;
use Module\Services\NotificationManager\Infrastructure\Factory\HotelContactModel;
use Module\Support\NotificationManager\Domain\Factory\NotifiableFactoryInterface;
use Module\Support\NotificationManager\Domain\Notifiable\Administrator;
use Module\Support\NotificationManager\Domain\Notifiable\ClientContact;
use Module\Support\NotificationManager\Domain\Notifiable\EmailAddress;
use Module\Support\NotificationManager\Domain\Notifiable\HotelAdministrator;
use Module\Support\NotificationManager\Domain\Notifiable\HotelContact;
use Module\Support\NotificationManager\Domain\ValueObject\NotifiableList;
use Module\Support\NotificationManager\Domain\ValueObject\NotifiableTypeEnum;

class NotifiableFactory implements NotifiableFactoryInterface
{
    public function fromType(NotifiableTypeEnum $type, ?string $data): NotifiableList
    {
        $data = json_decode($data);
        switch ($type) {
            case NotifiableTypeEnum::ADMINISTRATOR:
                return $this->mapAdministrator($data);
            case NotifiableTypeEnum::ADMINISTRATOR_GROUP:
                return $this->mapAdministratorGroup($data);
            case NotifiableTypeEnum::CLIENT_CONTACTS:
                return $this->mapClientContacts($data);
            case NotifiableTypeEnum::CLIENT_MANAGERS:
                throw new \Exception('To be implemented');
            case NotifiableTypeEnum::HOTEL_CONTACTS:
                return $this->mapHotelContacts($data);
            case NotifiableTypeEnum::HOTEL_ADMINISTRATORS:
                return $this->mapHotelAdministrators($data);
            case NotifiableTypeEnum::EMAIL_ADDRESS:
                return new NotifiableList([new EmailAddress($data->email, $data->name)]);
            case NotifiableTypeEnum::USER:
                throw new \Exception('To be implemented');
        }
    }

    private function mapAdministrator($data): NotifiableList
    {
        if (empty($data->administrator_id)) {
            return new NotifiableList();
        }

        $model = AdministratorModel::find((int)$data->administrator_id);
        if (!$model) {
            return new NotifiableList();
        }

        return new NotifiableList([
            new Administrator($model->id, $model->presentation, $model->email, $model->phone)
        ]);
    }

    private function mapAdministratorGroup($data): NotifiableList
    {
        if (empty($data->group_id)) {
            return new NotifiableList();
        }

        return $this->mapQuery(
            AdministratorModel::whereGroup((int)$data->group_id),
            fn($r) => new Administrator($r->id, $r->presentation, $r->email, $r->phone)
        );
    }

    private function mapClientContacts($data): NotifiableList
    {
        if (empty($data->client_id)) {
            return new NotifiableList();
        }

        return $this->mapQuery(
            ClientContactModel::whereGroup((int)$data->client_id),
            fn($r) => new ClientContact($r->id, $r->type, $r->value)
        );
    }

    private function mapHotelContacts($data): NotifiableList
    {
        if (empty($data->hotel_id)) {
            return new NotifiableList();
        }

        return $this->mapQuery(
            HotelContactModel::where('hotel_id', (int)$data->hotel_id),
            fn($r) => new HotelContact($r->id, $r->presentation, $r->email, $r->phone)
        );
    }

    private function mapHotelAdministrators($data): NotifiableList
    {
        if (empty($data->hotel_id)) {
            return new NotifiableList();
        }

        return $this->mapQuery(
            HotelAdministratorModel::where('hotel_id', (int)$data->hotel_id),
            fn($r) => new HotelAdministrator($r->id, $r->presentation, $r->email, $r->phone)
        );
    }

    private function mapQuery($query, callable $mapper): NotifiableList
    {
        return new NotifiableList($query->get()->map($mapper)->all());
    }
}
