<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Module\Shared\Enum\Hotel\ReviewStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotel_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');
            $table->unsignedInteger('booking_id')->nullable();
            $table->string('name');
            $table->string('text');
            $table->float('rating');
            $table->unsignedTinyInteger('status')->default(ReviewStatusEnum::NOT_PUBLIC);
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        $this->upReviewRatings();
    }

    private function upReviewRatings(): void
    {
        Schema::create('hotel_review_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('review_id');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('value');
            $table->timestamps();

            $table->foreign('review_id')
                ->references('id')
                ->on('hotel_reviews')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('hotel_review_ratings');
    }
};
