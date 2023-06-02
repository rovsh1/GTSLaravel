<?php

use Illuminate\Database\Migrations\Migration;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::table('translation_items')->insert([
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::DRAFT->name,
                'value_ru' => 'Черновик',
                'value_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::CREATED->name,
                'value_ru' => 'Новая',
                'value_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::PROCESSING->name,
                'value_ru' => 'В работе',
                'value_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::CANCELLED->name,
                'value_ru' => 'Отменена',
                'value_en' => 'Cancelled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::CONFIRMED->name,
                'value_ru' => 'Подтверждена',
                'value_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::NOT_CONFIRMED->name,
                'value_ru' => 'Не подтверждена',
                'value_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::INVOICED->name,
                'value_ru' => 'Выписан счет',
                'value_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::PAID->name,
                'value_ru' => 'Оплачен',
                'value_en' => 'Paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::PARTIALLY_PAID->name,
                'value_ru' => 'Частично оплачен',
                'value_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::CANCELLED_NO_FEE->name,
                'value_ru' => 'Отмена без штрафа',
                'value_en' => 'Cancelled no fee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::CANCELLED_FEE->name,
                'value_ru' => 'Отмена со штрафом',
                'value_en' => 'Cancelled fee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::REFUND_NO_FEE->name,
                'value_ru' => 'Возврат без штрафа',
                'value_en' => 'Refund no fee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::REFUND_FEE->name,
                'value_ru' => 'Возврат со штрафом',
                'value_en' => 'Refund fee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::WAITING_CONFIRMATION->name,
                'value_ru' => 'Ожидает подтверждения',
                'value_en' => 'Waiting confirmation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::WAITING_CANCELLATION->name,
                'value_ru' => 'Ожидает аннулирования',
                'value_en' => 'Waiting cancellation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => BookingStatusEnum::class . '::' . BookingStatusEnum::WAITING_PROCESSING->name,
                'value_ru' => 'Ожидает обработки',
                'value_en' => 'Waiting processing',
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
        \DB::table('translation_items')->truncate();
    }
};
