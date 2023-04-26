<script lang="ts" setup>
import { computed, ref } from 'vue'

import { DateTime } from 'luxon'

import BaseButton from '~resources/components/BaseButton.vue'
import { getEachMonthInYear } from '~resources/lib/date'
import FiltersSelect from '~resources/views/hotel/quotas/components/FiltersSelect.vue'
import { RoomID } from '~resources/views/hotel/quotas/lib'
import { roomsMock } from '~resources/views/hotel/quotas/lib/mock'

type OutputRangeValue = 1 | 3 | 6 | 12

type AvailabilityValue = 'sold' | 'stopped' | 'available'

export type FiltersPayload = {
  year: number
  month: number
  count: OutputRangeValue
  availability?: AvailabilityValue
  room?: RoomID
}

const emit = defineEmits<{
  (event: 'submit', value: FiltersPayload): void
}>()

type Year = {
  label: string
  value: number
}

const createYear = (value: number): Year => ({
  label: value.toString(),
  value,
})

const currentYear = new Date().getFullYear()
const yearsAddToCurrent = 5

const years = computed<Year[]>(() => Array
  .from({ length: yearsAddToCurrent })
  .map((item, index) => createYear(currentYear + index)))

const selectedYear = ref<Year['value']>(years.value[0].value)

type Month = {
  label: string
  value: number
}

const months = computed<Month[]>(() => {
  const year = DateTime.fromFormat(selectedYear.value.toString(), 'yyyy').toJSDate()
  return getEachMonthInYear(year).map((month) => ({
    label: month.toFormat('LLLL'),
    value: Number(month.toFormat('L')),
  }))
})

const selectedMonth = ref<Month['value']>(months.value[0].value)

type OutputRange = {
  label: string
  value: OutputRangeValue
}

const ranges: OutputRange[] = [
  { value: 1, label: '1 месяцу' },
  { value: 3, label: 'Кварталу' },
  { value: 6, label: '6 месяцев' },
  { value: 12, label: 'Году' },
]

const selectedRange = ref<OutputRange['value']>(ranges[0].value)

type AvailabilityOption = {
  value: AvailabilityValue
  label: string
}

const availabilityOptions: AvailabilityOption[] = [
  { value: 'sold', label: 'Проданные' },
  { value: 'stopped', label: 'Остановленные' },
  { value: 'available', label: 'Доступные' },
]

const selectedAvailabilityOption = ref<AvailabilityOption['value'] | null>(null)

const rooms = computed(() => roomsMock.map(({ id, name, custom_name: customName }) => ({
  value: id,
  label: `${name} (${customName})`,
})))

const selectedRoom = ref<RoomID | null>(null)

const submit = () => {
  emit('submit', {
    year: selectedYear.value,
    month: selectedMonth.value,
    count: selectedRange.value,
    availability: selectedAvailabilityOption.value ?? undefined,
    room: selectedRoom.value ?? undefined,
  })
}
</script>
<template>
  <div class="quotasFilters">
    <FiltersSelect
      :value="selectedYear"
      :options="years"
      label="Год"
      @input="(value) => selectedYear = value as unknown as number"
    />
    <FiltersSelect
      :options="months"
      label="Месяц"
      :value="selectedMonth"
      class="month"
      @input="(value) => selectedMonth = value as unknown as number"
    />
    <FiltersSelect
      :options="ranges"
      label="Выводить по"
      :value="selectedRange"
      @input="(value) => selectedRange = value as unknown as OutputRangeValue"
    />
    <FiltersSelect
      :options="availabilityOptions"
      label="Доступность"
      :value="selectedAvailabilityOption"
      @input="(value) => selectedAvailabilityOption = value as unknown as AvailabilityValue"
    />
    <FiltersSelect
      :options="rooms"
      label="Номер"
      :value="selectedRoom"
      @input="(value) => selectedRoom = Number(value) as unknown as RoomID"
    />
    <BaseButton label="Обновить" size="small" variant="outlined" @click="submit" />
  </div>
</template>
<style lang="scss" scoped>
.quotasFilters {
  display: flex;
  flex-wrap: wrap;
  gap: 1em;
  align-items: end;
}

.month {
  :deep(select) {
    text-transform: capitalize;
  }
}
</style>
