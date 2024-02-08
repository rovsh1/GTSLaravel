<?php

namespace Database\Seeders;

use App\Admin\Models\Administrator\Administrator;
use Illuminate\Database\Seeder;

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

        Administrator::create([
            'login' => 'anvar.zaitov@gotostans.com',
            'email' => 'anvar.zaitov@gotostans.com',
            'presentation' => 'Анвар Заитов',
            'phone' => '+998 90 9750111',
            'password' => '123456',
            'status' => 1,
            'superuser' => 1
        ]);
    }
}
