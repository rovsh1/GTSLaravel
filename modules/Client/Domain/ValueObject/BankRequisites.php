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
            cityId: $data['city_id'],
            inn: $data['inn'],
            okpo: $data['okpo'],
            correspondentAccount: $data['correspondent_account'],
            kpp: $data['kpp'],
            bankName: $data['bank_name'],
            currentAccount: $data['current_account'],
        );
    }

    public function toData(): array
    {
        return [
            'bik' => $this->bik,
            'city_id' => $this->cityId,
            'inn' => $this->inn,
            'okpo' => $this->okpo,
            'correspondent_account' => $this->correspondentAccount,
            'kpp' => $this->kpp,
            'bank_name' => $this->bankName,
            'current_account' => $this->currentAccount,
        ];
    }
}
