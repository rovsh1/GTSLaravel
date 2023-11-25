<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Sdk\Shared\Contracts\Support\SerializableInterface;

class BankRequisites implements ValueObjectInterface, SerializableInterface
{
    public function __construct(
        public readonly string $bik,
        public readonly ?string $cityName,
        public readonly string $inn,
        public readonly string $okpo,
        public readonly string $correspondentAccount,
        public readonly string $kpp,
        public readonly string $bankName,
        public readonly string $currentAccount,
    ) {}

    public static function deserialize(array $payload): static
    {
        return new static(
            bik: $payload['bik'],
            cityName: $payload['cityName'],
            inn: $payload['inn'],
            okpo: $payload['okpo'],
            correspondentAccount: $payload['correspondentAccount'],
            kpp: $payload['kpp'],
            bankName: $payload['bankName'],
            currentAccount: $payload['currentAccount'],
        );
    }

    public function serialize(): array
    {
        return [
            'bik' => $this->bik,
            'cityName' => $this->cityName,
            'inn' => $this->inn,
            'okpo' => $this->okpo,
            'correspondentAccount' => $this->correspondentAccount,
            'kpp' => $this->kpp,
            'bankName' => $this->bankName,
            'currentAccount' => $this->currentAccount,
        ];
    }
}
