<?php

namespace Module\Client\Infrastructure\Models;

use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\Model;

class Balance extends Model
{
    const UPDATED_AT = null;

    protected $table = 'client_balance';

    protected $fillable = [
        'client_id',
        'debit',
        'credit',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public static function clientBalanceSum(int $clientId): float
    {
        return (float)DB::selectOne(
            'SELECT (SUM(debit)-SUM(credit))'
            . ' FROM client_balance'
            . ' WHERE client_id=:client_id',
            ['client_id' => $clientId]
        );
    }
}
