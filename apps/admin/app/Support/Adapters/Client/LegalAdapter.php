<?php

namespace App\Admin\Support\Adapters\Client;

use Module\Client\Moderation\Application\Admin\Dto\LegalDto;
use Module\Client\Moderation\Application\Admin\Request\SetLegalBankRequisitesDto;
use Module\Client\Moderation\Application\Admin\UseCase\FindLegal;
use Module\Client\Moderation\Application\Admin\UseCase\SetLegalBankRequisites;

class LegalAdapter
{
    public function find(int $id): ?LegalDto
    {
        return app(FindLegal::class)->execute($id);
    }

    public function setBankRequisites(
        int $clientLegalId,
        string $bik,
        string $inn,
        string $okpo,
        string $correspondentAccount,
        string $kpp,
        string $bankName,
        string $currentAccount,
        string $cityName
    ): void {
        app(SetLegalBankRequisites::class)->execute(
            new SetLegalBankRequisitesDto(
                legalId: $clientLegalId,
                bik: $bik,
                bankName: $bankName,
                inn: $inn,
                okpo: $okpo,
                correspondentAccount: $correspondentAccount,
                kpp: $kpp,
                currentAccount: $currentAccount,
                cityName: $cityName,
            )
        );
    }
}
