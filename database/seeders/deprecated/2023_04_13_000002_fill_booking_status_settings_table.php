<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('booking_status_settings')->insert([
            $this->wrap(BookingStatusEnum::DRAFT, [
                'name_ru' => 'Черновик',
                'name_en' => null,
                'color' => 'secondary',
            ]),
            $this->wrap(BookingStatusEnum::CREATED, [
                'name_ru' => 'Новая',
                'name_en' => null,
                'color' => 'danger',
            ]),
            $this->wrap(BookingStatusEnum::PROCESSING, [
                'name_ru' => 'В работе',
                'name_en' => null,
                'color' => 'warning',
            ]),
            $this->wrap(BookingStatusEnum::CANCELLED, [
                'name_ru' => 'Отменена',
                'name_en' => 'Cancelled',
                'color' => 'dark',
            ]),
            $this->wrap(BookingStatusEnum::CONFIRMED, [
                'name_ru' => 'Подтверждена',
                'name_en' => null,
                'color' => 'success',
            ]),
            $this->wrap(BookingStatusEnum::NOT_CONFIRMED, [
                'name_ru' => 'Не подтверждена',
                'name_en' => null,
                'color' => 'secondary',
            ]),
            $this->wrap(BookingStatusEnum::CANCELLED_NO_FEE, [
                'name_ru' => 'Отмена без штрафа',
                'name_en' => 'Cancelled no fee',
                'color' => 'dark',
            ]),
            $this->wrap(BookingStatusEnum::CANCELLED_FEE, [
                'name_ru' => 'Отмена со штрафом',
                'name_en' => 'Cancelled fee',
                'color' => 'dark',
            ]),
            $this->wrap(BookingStatusEnum::WAITING_CONFIRMATION, [
                'name_ru' => 'Ожидает подтверждения',
                'name_en' => 'Waiting confirmation',
                'color' => 'secondary',
            ]),
            $this->wrap(BookingStatusEnum::WAITING_CANCELLATION, [
                'name_ru' => 'Ожидает аннулирования',
                'name_en' => 'Waiting cancellation',
                'color' => 'secondary',
            ]),
            $this->wrap(BookingStatusEnum::WAITING_PROCESSING, [
                'name_ru' => 'Ожидает обработки',
                'name_en' => 'Waiting processing',
                'color' => 'secondary',
            ]),
            $this->wrap(BookingStatusEnum::DELETED, [
                'name_ru' => 'Удалена',
                'name_en' => 'Deleted',
                'color' => 'secondary',
            ]),

            //order statuses
            $this->wrap(OrderStatusEnum::IN_PROGRESS, [
                'name_ru' => 'В работе',
                'name_en' => null,
                'color' => 'danger',
            ]),
            $this->wrap(OrderStatusEnum::WAITING_INVOICE, [
                'name_ru' => 'Ожидание инвойса',
                'name_en' => null,
                'color' => 'warning',
            ]),
            $this->wrap(OrderStatusEnum::INVOICED, [
                'name_ru' => 'Инвойс выставлен',
                'name_en' => null,
                'color' => 'info',
            ]),
            $this->wrap(OrderStatusEnum::PARTIAL_PAID, [
                'name_ru' => 'Частично оплачен',
                'name_en' => null,
                'color' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            $this->wrap(OrderStatusEnum::PAID, [
                'name_ru' => 'Оплачен',
                'name_en' => null,
                'color' => 'success',
            ]),
            $this->wrap(OrderStatusEnum::CANCELLED, [
                'name_ru' => 'Отменен без оплаты',
                'name_en' => null,
                'color' => 'secondary',
            ]),
            $this->wrap(OrderStatusEnum::REFUND_FEE, [
                'name_ru' => 'Возврат со штрафом',
                'name_en' => null,
                'color' => 'secondary',
            ]),
            $this->wrap(OrderStatusEnum::REFUND_NO_FEE, [
                'name_ru' => 'Возврат без штрафа',
                'name_en' => null,
                'color' => 'secondary',
            ]),
        ]);
    }

    private function wrap(BackedEnum $enum, array $data): array
    {
        return [
            ...$data,
            'value' => $enum,
            'type' => $enum::class,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('booking_status_settings')->truncate();
    }
};
