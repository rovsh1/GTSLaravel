<script setup lang="ts">
import { MaybeRef } from '@vueuse/core'

import { getCancelPeriodTypeName } from '~resources/views/booking/shared/lib/constants'

import { CancelPeriod, DailyMarkup } from '~api/hotel/markup-settings'

withDefaults(defineProps<{
  cancelPeriod: CancelPeriod
  dailyMarkups: DailyMarkup[]
  title: string
  loading?: MaybeRef<boolean>
}>(), {
  loading: false,
})

</script>

<template>
  <div>
    <div class="d-flex align-items-center">
      <h6 class="mb-0" style="margin-left: 0.5rem;">{{ title }}</h6>
    </div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="w-25" scope="col">Кол-во дней</th>
            <th class="w-25" scope="col">Наценка</th>
            <th class="w-auto" scope="col">Тип</th>
          </tr>
        </thead>
        <tbody :class="{ loading: loading }">
          <tr>
            <td>Не заезд</td>
            <td>
              {{ cancelPeriod.noCheckInMarkup.percent }} %
            </td>
            <td>
              {{ getCancelPeriodTypeName(cancelPeriod.noCheckInMarkup.cancelPeriodType) }}
            </td>
          </tr>
          <tr v-for="(dailyMarkup, idx) in dailyMarkups" :key="idx">
            <td>
              {{ dailyMarkup.daysCount }}
            </td>
            <td>
              <div class="text-nowrap">
                {{ dailyMarkup.percent }} %
              </div>
            </td>
            <td>{{ getCancelPeriodTypeName(dailyMarkup.cancelPeriodType) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped lang="scss">
tr td {
  vertical-align: middle;
}

a i {
  vertical-align: top;
  margin-left: 0.313rem;
  font-weight: bold;
  font-size: 1.125rem;
}
</style>
