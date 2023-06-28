<?php

declare(strict_types=1);

namespace Module\Client\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class BankRequisites implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        public readonly string $bik,
        public readonly ?int $cityId,
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
            cityId: $data['cityId'],
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
            'cityId' => $this->cityId,
            'inn' => $this->inn,
            'okpo' => $this->okpo,
            'correspondentAccount' => $this->correspondentAccount,
            'kpp' => $this->kpp,
            'bankName' => $this->bankName,
            'currentAccount' => $this->currentAccount,
        ];
    }
}
