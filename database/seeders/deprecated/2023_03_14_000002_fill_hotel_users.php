<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        $users = DB::connection('mysql_old')->table('users')
            ->whereExists(function (\Illuminate\Database\Query\Builder $query){
                $query->selectRaw(1)
                    ->from(\DB::connection()->getDatabaseName() . '.hotels')
                    ->whereColumn('hotel_id', 'hotels.id');
            })
            ->addSelect('users.*')
            ->addSelect('hotel_administrators.hotel_id')
            ->join('hotel_administrators', 'hotel_administrators.user_id', '=', 'users.id')
            ->get();

        foreach ($users as $r) {
            DB::table('hotel_administrators')
                ->insert([
                    'hotel_id' => $r->hotel_id,
                    'presentation' => $r->presentation,
                    'name' => $r->name,
                    'surname' => $r->surname,
                    'patronymic' => $r->patronymic,
                    'login' => $r->login,
                    'password' => $r->password,
                    'email' => $r->email,
                    'phone' => $r->phone,
                    'status' => 1,
                ]);
        }

        DB::table('hotel_administrators')
            ->insert([
                'hotel_id' => 61,
                'presentation' => 'developer',
                'login' => 'developer',
                'password' => \Hash::make('123456'),
                'status' => 1,
            ]);
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotel_administrators');
    }
};
