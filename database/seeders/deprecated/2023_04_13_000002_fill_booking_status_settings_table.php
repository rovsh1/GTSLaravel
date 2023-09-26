<?php

use Illuminate\Database\Migrations\Migration;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::table('booking_status_settings')->insert([
            [
                'value' => BookingStatusEnum::DRAFT->value,
                'name_ru' => 'Черновик',
                'name_en' => null,
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CREATED->value,
                'name_ru' => 'Новая',
                'name_en' => null,
                'color' => 'danger',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::PROCESSING->value,
                'name_ru' => 'В работе',
                'name_en' => null,
                'color' => 'warning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CANCELLED->value,
                'name_ru' => 'Отменена',
                'name_en' => 'Cancelled',
                'color' => 'dark',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CONFIRMED->value,
                'name_ru' => 'Подтверждена',
                'name_en' => null,
                'color' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::NOT_CONFIRMED->value,
                'name_ru' => 'Не подтверждена',
                'name_en' => null,
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CANCELLED_NO_FEE->value,
                'name_ru' => 'Отмена без штрафа',
                'name_en' => 'Cancelled no fee',
                'color' => 'dark',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CANCELLED_FEE->value,
                'name_ru' => 'Отмена со штрафом',
                'name_en' => 'Cancelled fee',
                'color' => 'dark',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::WAITING_CONFIRMATION->value,
                'name_ru' => 'Ожидает подтверждения',
                'name_en' => 'Waiting confirmation',
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::WAITING_CANCELLATION->value,
                'name_ru' => 'Ожидает аннулирования',
                'name_en' => 'Waiting cancellation',
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::WAITING_PROCESSING->value,
                'name_ru' => 'Ожидает обработки',
                'name_en' => 'Waiting processing',
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('booking_status_settings')->truncate();
    }
};
