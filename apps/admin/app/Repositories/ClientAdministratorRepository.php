<?php

declare(strict_types=1);

namespace App\Admin\Repositories;

use App\Admin\Models\Administrator\Administrator;

class ClientAdministratorRepository
{
    public function create(int $clientId, int $administratorId): void
    {
        \DB::table('administrator_clients')->insert([
            'client_id' => $clientId,
            'administrator_id' => $administratorId
        ]);
    }

    public function update(int $clientId, int $administratorId): void
    {
        \DB::table('administrator_clients')->updateOrInsert(
            ['client_id' => $clientId],
            ['client_id' => $clientId, 'administrator_id' => $administratorId],
        );
    }

    public function get(int $clientId): ?Administrator
    {
        return Administrator::query()
            ->join('administrator_clients', 'administrators.id', '=', 'administrator_clients.administrator_id')
            ->where('administrator_clients.client_id', $clientId)
            ->first();
    }
}
