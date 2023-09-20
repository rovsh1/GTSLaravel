<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('users');

        foreach ($q->cursor() as $user) {
            try {
                $createdAt = new \Carbon\Carbon($user->created);
            } catch (\Throwable $e) {
            }
            try {
                $updatedAt = new \Carbon\Carbon($user->updated);
            } catch (\Throwable $e) {
            }
            if ($createdAt->year < 1970 && $updatedAt->year > 1970) {
                $createdAt = $updatedAt;
            }
            if ($updatedAt->year < 1970 && $createdAt->year > 1970) {
                $updatedAt = $createdAt;
            }
            if ($createdAt->year < 1970) {
                $createdAt = now();
            }
            if ($updatedAt->year < 1970) {
                $updatedAt = now();
            }

            DB::table('users')->insert([
                'id' => $user->id,
                'client_id' => $user->client_id,
                'country_id' => $user->country_id,
                'name' => $user->name,
                'surname' => $user->surname,
                'patronymic' => $user->patronymic,
                'presentation' => $user->presentation,
                'gender' => $user->gender,
                'login' => $user->login,
                'password' => $user->password,
                'email' => $user->email,
                'phone' => $user->phone,
                'post_id' => $user->post_id,
                'address' => $user->address,
                'note' => $user->note,
                'status' => $user->status,
                'role' => $user->role,
                'birthday' => $user->birthday,
                'image' => $user->image,
                'recovery_hash' => $user->recovery_hash,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'deleted_at' => $user->deletion_mark ? now() : null,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->truncate();
    }
};
