<script setup lang="ts">
import { MaybeRef } from '@vueuse/core'

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'

import { CancelPeriod, DailyMarkup } from '~api/hotel/markup-settings'

import EditableCell from '~components/EditableCell.vue'

import { getCancelPeriodTypeName } from '../../../hotel-booking/show/composables/constants'

withDefaults(defineProps<{
  cancelPeriod: CancelPeriod
  dailyMarkups: DailyMarkup[]
  title: string
  loading?: MaybeRef<boolean>
}>(), {
  loading: false,
})

type DailyMarkupField = keyof DailyMarkup

defineEmits<{
  (event: 'editBase'): void
  (event: 'deleteBase'): void
  (event: 'edit', payload: { field: DailyMarkupField; value: number; index: number }): void
  (event: 'add'): void
  (event: 'delete', index: number): void
}>()

</script>

<template>
  <div>
    <div class="d-flex">
      <h6 style="margin-left: 0.5rem;">{{ title }}</h6>
      <EditTableRowButton
        can-add
        @add="$emit('add')"
        @edit="$emit('editBase')"
        @delete="$emit('deleteBase')"
      />
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Кол-во дней</th>
          <th scope="col">Наценка</th>
          <th scope="col">Тип</th>
          <th scope="col" />
        </tr>
      </thead>
      <tbody :class="{ loading: loading }">
        <tr>
          <td>По-умолчанию</td>
          <td>{{ cancelPeriod.noCheckInMarkup.percent }}%</td>
          <td>{{ getCancelPeriodTypeName(cancelPeriod.noCheckInMarkup.cancelPeriodType) }}</td>
          <td />
        </tr>
        <tr v-for="(dailyMarkup, idx) in dailyMarkups" :key="idx">
          <td>
            <EditableCell :value="dailyMarkup.daysCount" @change="value => $emit('edit', { field: 'daysCount', index: idx, value })" />
          </td>
          <td>
            <div class="text-nowrap">
              <EditableCell :value="dailyMarkup.percent" dimension="%" @change="value => $emit('edit', { field: 'percent', index: idx, value })" />
            </div>
          </td>
          <td>{{ getCancelPeriodTypeName(dailyMarkup.cancelPeriodType) }}</td>
          <td class="column-edit">
            <EditTableRowButton
              :can-edit="false"
              @delete="$emit('delete', idx)"
            />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped lang="scss">
a i {
  vertical-align: top;
  margin-left: 5px;
  font-weight: bold;
  font-size: 18px;
}
</style>
