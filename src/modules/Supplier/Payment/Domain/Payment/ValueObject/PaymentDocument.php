<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Domain\Payment\ValueObject;

use Sdk\Shared\ValueObject\File;

final class PaymentDocument
{
    public function __construct(
        private readonly string $name,
        private readonly File $file,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function file(): File
    {
        return $this->file;
    }
}
