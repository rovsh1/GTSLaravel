<?php

namespace App\Admin\Console\Commands\Admin;

use Illuminate\Console\Command;

use App\Admin\Models\Administrator;

class ChangePassword extends Command
{
    protected $signature = 'admin:change-password
		{login}
		{password}';

    protected $description = '';

    public function handle()
    {
        $user = Administrator::findByLogin($this->argument('login'));
        if (!$user) {
            return $this->error('Admin not found');
        }

        $user->update(['password' => $this->argument('password')]);

        return $this->info('Password changed');
    }
}
