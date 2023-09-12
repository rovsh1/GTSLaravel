<?php

namespace App\Admin\Support\Facades\Client;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setBankRequisites(int $clientLegalId, string $bik, string $inn, string $okpo, string $correspondentAccount, string $kpp, string $bankName, string $currentAccount, string $cityName)
 */
class LegalAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Client\LegalAdapter::class;
    }
}
