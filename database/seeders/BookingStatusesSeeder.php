<?php

namespace Database\Seeders;

use BackedEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Module\Booking\Shared\Infrastructure\Enum\StatusSettingsEntityEnum;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class BookingStatusesSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('booking_status_settings')->exists()) {
            return;
        }

        DB::table('booking_status_settings')->insert([
            $this->wrap(StatusEnum::DRAFT, [
                'name_ru' => 'Черновик',
                'name_en' => 'Draft',
                'color' => 'secondary',
            ]),
            $this->wrap(StatusEnum::CREATED, [
                'name_ru' => 'Новая',
                'name_en' => 'New',
                'color' => 'danger',
            ]),
            $this->wrap(StatusEnum::PROCESSING, [
                'name_ru' => 'В работе',
                'name_en' => 'Processing',
                'color' => 'warning',
            ]),
            $this->wrap(StatusEnum::CANCELLED, [
                'name_ru' => 'Отменена',
                'name_en' => 'Cancelled',
                'color' => 'dark',
            ]),
            $this->wrap(StatusEnum::CONFIRMED, [
                'name_ru' => 'Подтверждена',
                'name_en' => 'Confirmed',
                'color' => 'success',
            ]),
            $this->wrap(StatusEnum::NOT_CONFIRMED, [
                'name_ru' => 'Не подтверждена',
                'name_en' => 'Not confirmed',
                'color' => 'secondary',
            ]),
            $this->wrap(StatusEnum::CANCELLED_NO_FEE, [
                'name_ru' => 'Отмена без штрафа',
                'name_en' => 'Cancelled no fee',
                'color' => 'dark',
            ]),
            $this->wrap(StatusEnum::CANCELLED_FEE, [
                'name_ru' => 'Отмена со штрафом',
                'name_en' => 'Cancelled fee',
                'color' => 'dark',
            ]),
            $this->wrap(StatusEnum::WAITING_CONFIRMATION, [
                'name_ru' => 'Ожидает подтверждения',
                'name_en' => 'Waiting confirmation',
                'color' => 'secondary',
            ]),
            $this->wrap(StatusEnum::WAITING_CANCELLATION, [
                'name_ru' => 'Ожидает аннулирования',
                'name_en' => 'Waiting cancellation',
                'color' => 'secondary',
            ]),
            $this->wrap(StatusEnum::WAITING_PROCESSING, [
                'name_ru' => 'Ожидает обработки',
                'name_en' => 'Waiting processing',
                'color' => 'secondary',
            ]),
            $this->wrap(StatusEnum::DELETED, [
                'name_ru' => 'Удалена',
                'name_en' => 'Deleted',
                'color' => 'secondary',
            ]),

            //order statuses
            $this->wrap(OrderStatusEnum::IN_PROGRESS, [
                'name_ru' => 'В работе',
                'name_en' => 'In progress',
                'color' => 'danger',
            ]),
            $this->wrap(OrderStatusEnum::WAITING_INVOICE, [
                'name_ru' => 'Ожидание инвойса',
                'name_en' => 'Waiting invoice',
                'color' => 'warning',
            ]),
            $this->wrap(OrderStatusEnum::INVOICED, [
                'name_ru' => 'Инвойс выставлен',
                'name_en' => 'Invoiced',
                'color' => 'info',
            ]),
            $this->wrap(OrderStatusEnum::PARTIAL_PAID, [
                'name_ru' => 'Частично оплачен',
                'name_en' => 'Partial paid',
                'color' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            $this->wrap(OrderStatusEnum::PAID, [
                'name_ru' => 'Оплачен',
                'name_en' => 'Paid',
                'color' => 'success',
            ]),
            $this->wrap(OrderStatusEnum::CANCELLED, [
                'name_ru' => 'Отменен без оплаты',
                'name_en' => 'Cancelled',
                'color' => 'secondary',
            ]),
            $this->wrap(OrderStatusEnum::REFUND_FEE, [
                'name_ru' => 'Возврат со штрафом',
                'name_en' => 'Refund fee',
                'color' => 'secondary',
            ]),
            $this->wrap(OrderStatusEnum::REFUND_NO_FEE, [
                'name_ru' => 'Возврат без штрафа',
                'name_en' => 'Refund no fee',
                'color' => 'secondary',
            ]),
        ]);
    }

    private function wrap(BackedEnum $enum, array $data): array
    {
        $type = match ($enum::class) {
            OrderStatusEnum::class => StatusSettingsEntityEnum::ORDER,
            StatusEnum::class => StatusSettingsEntityEnum::BOOKING,
        };

        return [
            ...$data,
            'entity_type' => $type->value,
            'status' => $enum->value,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
