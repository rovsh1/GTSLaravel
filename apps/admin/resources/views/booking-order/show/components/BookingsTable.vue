<script setup lang="ts">

import { OrderBooking } from '~api/order/booking'

import { formatDate, formatPeriod } from '~lib/date'
import { formatBookingPrice } from '~lib/price'

defineProps<{
  orderBookings: OrderBooking[]
  canEdit: boolean
}>()

defineEmits<{
  (event: 'edit', guest: any): void
  (event: 'delete', guest: any): void
}>()

const formatBookingPeriod = (booking: OrderBooking): string | null => {
  if (!booking.bookingPeriod) {
    return null
  }
  return formatPeriod({ date_start: booking.bookingPeriod.dateFrom, date_end: booking.bookingPeriod.dateTo })
}

</script>

<template>
  <table class="table table-striped mb-0">
    <thead>
      <tr>
        <th>№ брони</th>
        <th class="column-text">Название услуги</th>
        <th class="column-text">Период</th>
        <th class="column-text">Стоимость (приход/расход)</th>
        <th class="column-text">Статус</th>
        <th class="column-text">Отмена без штрафа (до.)</th>
        <!-- <th v-if="canEdit" /> -->
      </tr>
    </thead>
    <tbody>
      <template v-if="orderBookings.length > 0">
        <tr v-for="(booking) in orderBookings" :key="booking.id">
          <td><a href="#">{{ booking.id }}</a></td>
          <td>{{ booking.serviceInfo.name }}</td>
          <td>{{ formatBookingPeriod(booking) }}</td>
          <td>
            {{ formatBookingPrice(booking.prices.clientPrice) }} /
            {{ formatBookingPrice(booking.prices.supplierPrice) }}
          </td>
          <td>
            <span
              :class="`badge rounded-pill px-2
            ${(booking.status.color ? `text-bg-${booking.status.color}` : 'text-bg-secondary')}`"
            >
              {{ booking.status.name }}
            </span>
          </td>
          <td>
            {{ booking.cancelConditions && booking.cancelConditions.cancelNoFeeDate
              ? formatDate(booking.cancelConditions.cancelNoFeeDate) : '' }}
          </td>
          <!-- <td v-if="canEdit" class="column-edit">
            <EditTableRowButton
              @edit="$emit('edit', booking)"
              @delete="$emit('delete', booking)"
            />
          </td> -->
        </tr>
      </template>
      <template v-else>
        <tr>
          <td colspan="6" class="text-center">Брони не добавлены</td>
        </tr>
      </template>
    </tbody>
  </table>
</template>
