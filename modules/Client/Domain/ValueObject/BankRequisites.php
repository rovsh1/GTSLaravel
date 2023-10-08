<?php

declare(strict_types=1);

namespace Module\Client\Domain\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

class BankRequisites implements ValueObjectInterface, SerializableDataInterface
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

    public static function fromData(array $data): static
    {
        return new static(
            bik: $data['bik'],
            cityName: $data['cityName'],
            inn: $data['inn'],
            okpo: $data['okpo'],
            correspondentAccount: $data['correspondentAccount'],
            kpp: $data['kpp'],
            bankName: $data['bankName'],
            currentAccount: $data['currentAccount'],
        );
    }

    public function toData(): array
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
