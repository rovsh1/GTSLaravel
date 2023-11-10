<script setup lang="ts">
import { computed } from 'vue'

import { getCancelPeriodTypeName } from '~resources/views/booking/shared/lib/constants'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { CancelConditions } from '~api/booking/hotel/details'

import { formatDate } from '~lib/date'

const bookingStore = useBookingStore()

const cancelConditions = computed<CancelConditions | null>(() => bookingStore.booking?.cancelConditions || null)
</script>

<template>
  <table class="table-params">
    <tbody>
      <tr>
        <th>Отмена без штрафа</th>
        <td>до {{ cancelConditions?.cancelNoFeeDate ? formatDate(cancelConditions.cancelNoFeeDate) : '-' }}</td>
      </tr>
      <tr v-for="dailyMarkup in cancelConditions?.dailyMarkups" :key="dailyMarkup.daysCount">
        <th>За {{ dailyMarkup.daysCount }} дней</th>
        <td>
          {{ dailyMarkup.percent }}% {{ getCancelPeriodTypeName(dailyMarkup.cancelPeriodType) }}
        </td>
      </tr>
      <tr>
        <th>Незаезд</th>
        <td v-if="cancelConditions">
          {{ cancelConditions.noCheckInMarkup.percent }}%
          {{ getCancelPeriodTypeName(cancelConditions.noCheckInMarkup.cancelPeriodType) }}
        </td>
      </tr>
    </tbody>
  </table>
</template>
