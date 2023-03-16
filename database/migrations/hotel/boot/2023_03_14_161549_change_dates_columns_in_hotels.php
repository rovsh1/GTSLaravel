<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dateTime('created')->nullable()->default(null)->change();
            $table->timestamps();
        });

        \DB::transaction(function () {
            foreach (\App\Admin\Models\Hotel\Hotel::cursor() as $hotel) {
                $data = [
                    'id' => $hotel->id,
                    'created_at' => $hotel->created,
                    'updated_at' => $hotel->updated,

                    //hack обязательные поля, не будут обновляться, но требуются для mysql
                    'city_id' => 0,
                    'type_id' => 0,
                    'name' => '',
                    'rating' => 0,
                    'address' => '',
                    'geolocation' => '',
                    'citycenter_distance' => 0,
                    'latitude' => 0,
                    'longitude' => 0,
                    'zipcode' => '',
                    'status' => 0,
                    'visible_for' => 0,
                ];
                //hack $hotel->update() почему то не обновляет поля created_at, updated_at внутри миграции
                \App\Admin\Models\Hotel\Hotel::upsert($data, 'id', ['created_at', 'updated_at']);
            }
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('created');
            $table->dropColumn('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
