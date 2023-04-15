<?php

namespace Module\Services\MailManager\Domain\ValueObject;

class AddressList implements \Iterator
{
    private int $position = 0;

    private array $list = [];

    public function __construct(array $addresses = [])
    {
        $this->set($addresses);
    }

    public function set(array $addresses): void
    {
        $this->list = [];
        foreach ($addresses as $address) {
            $this->add($address);
        }
    }

    public function add(EmailAddress|string $address): void
    {
        if (!$this->exists($address)) {
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
}