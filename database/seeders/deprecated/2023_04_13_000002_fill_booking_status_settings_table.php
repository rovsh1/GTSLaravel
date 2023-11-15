<?php

use Illuminate\Database\Migrations\Migration;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\Order\OrderStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::table('booking_status_settings')->insert([
            [
                'value' => BookingStatusEnum::DRAFT,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Черновик',
                'name_en' => null,
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CREATED,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Новая',
                'name_en' => null,
                'color' => 'danger',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::PROCESSING,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'В работе',
                'name_en' => null,
                'color' => 'warning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CANCELLED,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Отменена',
                'name_en' => 'Cancelled',
                'color' => 'dark',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CONFIRMED,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Подтверждена',
                'name_en' => null,
                'color' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::NOT_CONFIRMED,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Не подтверждена',
                'name_en' => null,
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CANCELLED_NO_FEE,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Отмена без штрафа',
                'name_en' => 'Cancelled no fee',
                'color' => 'dark',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::CANCELLED_FEE,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Отмена со штрафом',
                'name_en' => 'Cancelled fee',
                'color' => 'dark',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::WAITING_CONFIRMATION,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Ожидает подтверждения',
                'name_en' => 'Waiting confirmation',
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::WAITING_CANCELLATION,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Ожидает аннулирования',
                'name_en' => 'Waiting cancellation',
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::WAITING_PROCESSING,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Ожидает обработки',
                'name_en' => 'Waiting processing',
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => BookingStatusEnum::DELETED,
                'type' => BookingStatusEnum::class,
                'name_ru' => 'Удалена',
                'name_en' => 'Deleted',
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //order statuses
            [
                'value' => OrderStatusEnum::IN_PROGRESS,
                'type' => OrderStatusEnum::class,
                'name_ru' => 'В работе',
                'name_en' => null,
                'color' => 'danger',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::WAITING_INVOICE,
                'type' => OrderStatusEnum::class,
                'name_ru' => 'Ожидание инвойса',
                'name_en' => null,
                'color' => 'warning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::INVOICED,
                'type' => OrderStatusEnum::class,
                'name_ru' => 'Инвойс выставлен',
                'name_en' => null,
                'color' => 'info',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::PARTIAL_PAID,
                'type' => OrderStatusEnum::class,
                'name_ru' => 'Частично оплачен',
                'name_en' => null,
                'color' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::PAID,
                'type' => OrderStatusEnum::class,
                'name_ru' => 'Оплачен',
                'name_en' => null,
                'color' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::CANCELLED,
                'type' => OrderStatusEnum::class,
                'name_ru' => 'Отменен без оплаты',
                'name_en' => null,
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::REFUND_FEE,
                'type' => OrderStatusEnum::class,
                'name_ru' => 'Возврат со штрафом',
                'name_en' => null,
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'value' => OrderStatusEnum::REFUND_NO_FEE,
                'type' => OrderStatusEnum::class,
                'name_ru' => 'Возврат без штрафа',
                'name_en' => null,
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
