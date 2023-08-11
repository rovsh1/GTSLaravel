<?php

declare(strict_types=1);

namespace Module\Shared\Support\Repository;

use Illuminate\Support\Facades\DB;

trait CanTransactionTrait
{
    public function startTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commitTransaction(): void
    {
        DB::commit();
    }

    public function rollBackTransaction(): void
    {
        DB::rollBack();
    }
}
