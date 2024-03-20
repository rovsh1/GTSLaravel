<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedSmallInteger('country_id')->after('city_id');
        });

        $query = DB::table('clients')
            ->addSelect('clients.id')
            ->addSelect('r_cities.country_id')
            ->join('r_cities', 'r_cities.id', 'clients.city_id');

        foreach ($query->cursor() as $client) {
            DB::table('clients')->where('id', $client->id)->update(['country_id' => $client->country_id]);
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('country_id')
                ->references('id')
                ->on('r_countries')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->dropForeign('clients_city_id_foreign');
            $table->dropColumn('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });
    }
};
