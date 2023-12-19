<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ApplicationConstantsSeeder::class,
            CompanyRequisitesSeeder::class,
            ReferencesSeeder::class,
            DeveloperSeeder::class,
            DeprecatedSeeder::class,
            TestDataSeeder::class,
            TranslatorSeeder::class,
            BookingStatusesSeeder::class,
            CancelReasonSeeder::class,
        ]);
    }
}
