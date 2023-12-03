<script setup lang="ts">
import { computed } from 'vue'

import { getCancelPeriodTypeName, getDaysWord } from '~resources/views/booking/shared/lib/constants'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { formatDate } from '~lib/date'

const bookingStore = useBookingStore()

const cancelConditions = computed(() => bookingStore.booking?.cancelConditions)
</script>

<template>
  <div class="control-panel-section-title">
    <span><h6 class="mb-0">Условия отмены</h6></span>
  </div>
  <hr>
  <table class="table-params">
    <tbody>
      <tr>
        <th>Отмена без штрафа</th>
        <td>до {{ cancelConditions?.cancelNoFeeDate ? formatDate(cancelConditions?.cancelNoFeeDate) : '-' }}</td>
      </tr>
      <tr>
        <th>Незаезд</th>
        <td v-if="cancelConditions">
          {{ cancelConditions.noCheckInMarkup.value }}{{ cancelConditions.noCheckInMarkup.valueType }}
          {{ getCancelPeriodTypeName(cancelConditions.noCheckInMarkup.cancelPeriodType) }}
        </td>
      </tr>
      <tr v-for="dailyMarkup in cancelConditions?.dailyMarkups" :key="dailyMarkup.daysCount">
        <th>За {{ dailyMarkup.daysCount }} {{ getDaysWord(dailyMarkup.daysCount) }}</th>
        <td>
          {{ dailyMarkup.value }}{{ dailyMarkup.valueType }} {{ getCancelPeriodTypeName(dailyMarkup.cancelPeriodType) }}
        </td>
      </tr>
    </tbody>
  </table>
</template>
