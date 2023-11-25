<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\ValueObject;

use Sdk\Shared\Contracts\Support\SerializableInterface;

final class AddressList implements \Iterator, SerializableInterface
{
    private int $position = 0;

    private array $list = [];

    public function __construct(iterable $addresses = [])
    {
//        if (empty($addresses)) {
//            throw new \Exception('AddressList cant be empty');
//        }

        foreach ($addresses as $address) {
            $this->list[] = $address instanceof EmailAddress ? $address : new EmailAddress($address);
        }
    }

    public function exists(EmailAddress|string $address): bool
    {
        $searchValue = is_string($address) ? $address : $address->email();
        foreach ($this->list as $address) {
            if ($address->email() === $searchValue) {
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
        return array_map(fn(EmailAddress $address) => $address->email(), $this->list);
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