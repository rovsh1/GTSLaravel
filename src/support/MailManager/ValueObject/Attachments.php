<?php

declare(strict_types=1);

namespace Support\MailManager\ValueObject;

use Sdk\Module\Support\AbstractValueObjectCollection;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\File;

final class Attachments extends AbstractValueObjectCollection implements SerializableInterface
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof File) {
            throw new \InvalidArgumentException(File::class . ' instance expected');
        }
    }

    public function serialize(): array
    {
        return array_map(fn(File $file) => $file->guid(), $this->items);
    }

    public static function deserialize(array $payload): static
    {
        return new Attachments(array_map(fn(string $guid) => new File($guid), $payload));
    }
}