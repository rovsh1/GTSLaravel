<?php

namespace App\Admin\Support\Facades\Client;

use Illuminate\Support\Facades\Facade;
use Module\Client\Application\Dto\LegalDto;

/**
 * @method static void setBankRequisites(int $clientLegalId, string $bik, string $inn, string $okpo, string $correspondentAccount, string $kpp, string $bankName, string $currentAccount, string $cityName)
 * @method static LegalDto|null find(int $id)
 */
class LegalAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Client\LegalAdapter::class;
    }
}
