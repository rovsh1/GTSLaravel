<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traveline_reservations_old', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('reservation_id');
            $table->bigInteger('hotel_id');
            $table->string('status');
            $table->json('data');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });

        $q = DB::connection('mysql_old')
            ->addSelect('traveline_reservations.*')
            ->addSelect('reservation.hotel_id')
            ->table('traveline_reservations')
            ->join('reservation', 'reservation.id', 'traveline_reservations.reservation_id');

        foreach ($q->cursor() as $reservation) {
            DB::table('traveline_reservations_old')->insert([
                'id' => $reservation->id,
                'reservation_id' => $reservation->reservation_id,
                'hotel_id' => $reservation->hotel_id,
                'status' => $reservation->status,
                'data' => $reservation->data,
                'accepted_at' => $reservation->accepted_at,
                'created_at' => $reservation->created_at,
                'updated_at' => $reservation->updated,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traveline_reservations_old');
    }
};
