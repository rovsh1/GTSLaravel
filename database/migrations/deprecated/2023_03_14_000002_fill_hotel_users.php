<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $users = DB::connection('mysql_old')->table('users')
            ->addSelect('users.*')
            ->addSelect('hotel_administrators.hotel_id')
            ->join('hotel_administrators', 'hotel_administrators.user_id', '=', 'users.id')
            ->get();
        foreach ($users as $r) {
            DB::table('hotel_users')
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
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotel_users');
    }
};
