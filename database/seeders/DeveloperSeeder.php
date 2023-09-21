<?php

namespace Database\Seeders;

use App\Admin\Models\Administrator\Administrator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeveloperSeeder extends Seeder
{
    public function run(): void
    {
        if (Administrator::exists()) {
            return;
        }

        Administrator::create([
            'login' => 'developer',
            'presentation' => 'developer',
            'password' => '123456',
            'status' => 1,
            'superuser' => 1
        ]);
    }
}
