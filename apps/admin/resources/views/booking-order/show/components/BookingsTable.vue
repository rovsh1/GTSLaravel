<script setup lang="ts">

import { OrderBooking } from '~api/order/booking'

import { formatPeriod } from '~lib/date'

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
  <table class="table table-striped">
    <thead>
      <tr>
        <th>№</th>
        <th class="column-text">Название</th>
        <th class="column-text">Период</th>
        <th class="column-text">Стоимость</th>
        <th class="column-text">Статус</th>
        <th class="column-text">Отмена</th>
        <!-- <th v-if="canEdit" /> -->
      </tr>
    </thead>
    <tbody>
      <template v-if="orderBookings.length > 0">
        <tr v-for="(booking) in orderBookings" :key="booking.id">
          <td>{{ booking.id }}</td>
          <td>{{ booking.serviceInfo.name }}</td>
          <td>{{ formatBookingPeriod(booking) }}</td>
          <td>{{ booking.prices.clientPrice.calculatedValue }}</td>
          <td>{{ booking.status.name }}</td>
          <td />
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
