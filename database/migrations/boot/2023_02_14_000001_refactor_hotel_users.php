<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::rename('hotel_administrators', 'deprecated_hotel_administrators');

        $this->createUsersTable();

        $this->fillNewTable();
    }

    private function createUsersTable()
    {
        Schema::create('hotel_users', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('hotel_id')->unsigned();
            $table->string('presentation', 100);
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('patronymic', 100);
            $table->string('login', 50);
            $table->string('password', 60);
            $table->string('email', 50);
            $table->string('phone', 50);
            $table->tinyInteger('status')->unsigned()->default(0);

            $table->foreign('hotel_id', 'fkey_hotel_id')
                ->references('id')
                ->on('hotels')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function fillNewTable()
    {
        $users = DB::table('users')
            ->addSelect('users.*')
            ->addSelect('deprecated_hotel_administrators.hotel_id')
            ->join('deprecated_hotel_administrators', 'deprecated_hotel_administrators.user_id', '=', 'users.id')
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

        DB::table('users')
            ->where('role', 2)
            ->delete();
    }

    public function down() {}
};
