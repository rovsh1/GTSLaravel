<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\ValueObject;

use Module\Shared\Contracts\Support\Serializable;

final class AddressList implements \Iterator, Serializable
{
    private int $position = 0;

    private array $list = [];

    public function __construct(array $addresses = [])
    {
        foreach ($addresses as $address) {
            $this->list[] = is_string($address) ? new EmailAddress($address) : $address;
        }
    }

    public function exists(EmailAddress|string $address): bool
    {
        $searchValue = is_string($address) ? $address : $address->value();
        foreach ($this->list as $address) {
            if ($address->value() === $searchValue) {
                return true;
            }
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return empty($this->list);
    }

    public function toArray(): array
    {
        return array_map(fn(EmailAddress $address) => $address->value(), $this->list);
    }

    public function current(): EmailAddress
    {
        return $this->list[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->list[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function serialize(): array
    {
        return $this->toArray();
    }

    public static function deserialize(array $payload): AddressList
    {
        return new AddressList($payload);
    }
}