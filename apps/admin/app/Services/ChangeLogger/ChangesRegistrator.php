<?php

namespace App\Admin\Services\ChangeLogger;

use App\Admin\Services\ChangeLogger\Changes\ChangesInterface;
use App\Admin\Support\Facades\AppContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChangesRegistrator
{
    public function register(ChangesInterface $changes): void
    {
//        if ($this->changesRepository->isEmpty()) {
//            return;
//        }
//
//        $payload = [];
//        foreach ($this->changesRepository->get() as $changes) {
//            $payload[] = $changes->serialize();
//        }
        $payload = $changes->payload();

        DB::table('administrator_journal_log')
            ->insert([
                'administrator_id' => Auth::id(),
                'event' => $changes->event()->name,
                'entity_id' => $changes->entityId(),
                'entity_class' => $changes->entityClass(),
                'payload' => $payload ? json_encode($payload) : null,
                'context' => json_encode(AppContext::toArray())
            ]);
    }
}