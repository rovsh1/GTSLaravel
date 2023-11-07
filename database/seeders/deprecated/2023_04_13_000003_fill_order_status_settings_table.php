<?php

use Illuminate\Database\Migrations\Migration;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\Booking\OrderStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::table('order_status_settings')->insert([
            [
                'value' => OrderStatusEnum::IN_PROGRESS,
                'name_ru' => 'В работе',
                'name_en' => null,
                'color' => 'danger',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::WAITING_INVOICE,
                'name_ru' => 'Ожидание инвойса',
                'name_en' => null,
                'color' => 'warning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::INVOICED,
                'name_ru' => 'Инвойс выставлен',
                'name_en' => null,
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::PARTIAL_PAID,
                'name_ru' => 'Частично оплачен',
                'name_en' => null,
                'color' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::PAID,
                'name_ru' => 'Оплачен',
                'name_en' => null,
                'color' => 'success',
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
        \DB::table('order_status_settings')->truncate();
    }
};
