<script setup lang="ts">
import { computed } from 'vue'

import { CancelConditionsValueType } from '~resources/lib/constants'
import { useCurrencyStore } from '~resources/store/currency'
import { getCancelPeriodTypeName, getDaysWord } from '~resources/views/booking/shared/lib/constants'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { Currency } from '~api/models'

import { formatDate } from '~lib/date'
import { formatPrice } from '~lib/price'

const bookingStore = useBookingStore()
const { getCurrencyByCodeChar } = useCurrencyStore()

const cancelConditions = computed(() => bookingStore.booking?.cancelConditions)

const clientCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.clientPrice.currency.value),
)
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
        <td v-if="cancelConditions?.noCheckInMarkup">
          <template
            v-if="cancelConditions.noCheckInMarkup.valueType
              === CancelConditionsValueType.PERCENT"
          >
            {{ cancelConditions.noCheckInMarkup.value }}%
            {{ getCancelPeriodTypeName(cancelConditions.noCheckInMarkup.cancelPeriodType) }}
          </template>
          <template v-else>
            {{ formatPrice(cancelConditions.noCheckInMarkup.value, clientCurrency?.sign) }}
            {{ getCancelPeriodTypeName(cancelConditions.noCheckInMarkup.cancelPeriodType) }}
          </template>
        </td>
        <td v-else>-</td>
      </tr>
      <tr v-for="dailyMarkup in cancelConditions?.dailyMarkups" :key="dailyMarkup.daysCount">
        <th>За {{ dailyMarkup.daysCount }} {{ getDaysWord(dailyMarkup.daysCount) }}</th>
        <td>
          <template
            v-if="dailyMarkup.valueType
              === CancelConditionsValueType.PERCENT"
          >
            {{ dailyMarkup.value }}%
            {{ getCancelPeriodTypeName(dailyMarkup.cancelPeriodType) }}
          </template>
          <template v-else>
            {{ formatPrice(dailyMarkup.value, clientCurrency?.sign) }}
            {{ getCancelPeriodTypeName(dailyMarkup.cancelPeriodType) }}
          </template>
        </td>
      </tr>
    </tbody>
  </table>
</template>
