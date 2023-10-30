<?php

namespace Module\Support\MailManager\Infrastructure\Service\RecipientsFinder;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\AddressResolverInterface;
use Module\Support\MailManager\Domain\ValueObject\AddressList;

class AddressResolver implements AddressResolverInterface
{
    public function resolve(RecipientInterface $recipient): ?AddressList
    {
        return match (true) {
            $recipient instanceof Recipient\Administrator => $this->resolveAdministrator($recipient->id()),
            $recipient instanceof Recipient\AdministratorGroup => $this->resolveAdministratorGroup($recipient->id()),
            $recipient instanceof Recipient\Administrators => $this->resolveAdministrators(),
            $recipient instanceof Recipient\Client => $this->resolveClient($recipient->id()),
            $recipient instanceof Recipient\Email => new AddressList([$recipient->id()]),
            $recipient instanceof Recipient\Hotel => $this->resolveHotel($recipient->id()),
            $recipient instanceof Recipient\HotelContacts => $this->resolveHotelContacts($recipient->id()),
            $recipient instanceof Recipient\HotelEmployees => $this->resolveHotelEmployees($recipient->id()),
            $recipient instanceof Recipient\HotelManagers => $this->resolveHotelManagers($recipient->id()),
            $recipient instanceof Recipient\User => $this->resolveUser($recipient->id()),
            default => throw new \LogicException($recipient::class . ' resolve method not implemented'),
        };
    }

    private function resolveAdministrator(int $administratorId): ?AddressList
    {
        return $this->fromValue(DB::table('administrators')->where('id', $administratorId)->value('email'));
    }

    private function resolveAdministratorGroup(int $groupId): ?AddressList
    {
        return null;
    }

    private function resolveAdministrators(): ?AddressList
    {
        return $this->fromPluck(DB::table('administrators')->pluck('email'));
    }

    private function resolveClient(int $clientId): ?AddressList
    {
        return null;
    }

    private function resolveHotel(int $hotelId): ?AddressList
    {
        return null;
    }

    private function resolveHotelContacts(int $hotelId): ?AddressList
    {
        return null;
    }

    private function resolveHotelEmployees(int $hotelId): ?AddressList
    {
        return null;
    }

    private function resolveHotelManagers(int $hotelId): ?AddressList
    {
        return null;
    }

    private function resolveUser(int $userId): ?AddressList
    {
        return null;
    }

    private function fromValue(?string $value): ?AddressList
    {
        return empty($value) ? null : new AddressList([$value]);
    }

    private function fromPluck(Collection $collection): ?AddressList
    {
        $values = array_filter($collection->all());
        if (empty($values)) {
            return null;
        }

        return new AddressList($collection->all());
    }
}