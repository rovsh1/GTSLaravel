<script lang="ts" setup>
import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { DateTime } from 'luxon'

import BootstrapButton from '~resources/components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import { getEachMonthInYear } from '~resources/lib/date'
import { MonthNumber, Room } from '~resources/lib/models'

import FiltersSelect from '../FiltersSelect.vue'

import { RoomID } from '../lib'
import {
  AvailabilityValue,
  createYear,
  currentMonth,
  currentYear,
  defaultFiltersPayload,
  FiltersPayload,
  Month,
  MonthsCount,
  Year,
} from './lib'

const props = withDefaults(defineProps<{
  rooms: Room[]
  loading?: MaybeRef<boolean>
}>(), {
  loading: false,
})

const emit = defineEmits<{
  (event: 'submit', value: FiltersPayload): void
}>()

const selectedYear = ref<Year['value']>(defaultFiltersPayload.year)

const yearsAddToCurrent = 5

const years = computed<Year[]>(() => Array
  .from({ length: yearsAddToCurrent })
  .map((item, index) => createYear(currentYear + index)))

const months = computed<Month[]>(() => {
  const year = DateTime.fromFormat(selectedYear.value.toString(), 'yyyy').toJSDate()
  return getEachMonthInYear(year).map((month) => ({
    label: month.toFormat('LLLL'),
    value: Number(month.toFormat('L')) as MonthNumber,
  }))
})

const selectedMonth = ref<Month['value']>(currentMonth)

type MonthsCountOption = {
  label: string
  value: MonthsCount
}

const monthsCountOptions: MonthsCountOption[] = [
  { value: 1, label: '1 месяцу' },
  { value: 3, label: 'Кварталу' },
  { value: 6, label: '6 месяцев' },
  { value: 12, label: 'Году' },
]

const selectedMonthsCount = ref<MonthsCountOption['value']>(defaultFiltersPayload.monthsCount)

type AvailabilityOption = {
  value: AvailabilityValue
  label: string
}

const availabilityOptions: AvailabilityOption[] = [
  { value: 'sold', label: 'Проданные' },
  { value: 'stopped', label: 'Остановленные' },
  { value: 'available', label: 'Доступные' },
]

const selectedAvailabilityOption = ref<AvailabilityOption['value'] | ''>('')

const rooms = computed(() => props.rooms.map(({ id, name, custom_name: customName }) => ({
  value: id,
  label: `${name} (${customName})`,
})))

const selectedRoom = ref<RoomID | ''>('')

const handleRoomInput = (value: string | number) => {
  const numericValue = Number(value)
  if (value === '' || Number.isNaN(numericValue)) {
    selectedRoom.value = ''
  } else {
    selectedRoom.value = numericValue
  }
}

const submit = () => {
  emit('submit', {
    year: selectedYear.value,
    month: selectedMonth.value,
    monthsCount: selectedMonthsCount.value,
    availability: selectedAvailabilityOption.value === ''
      ? undefined : selectedAvailabilityOption.value,
    room: selectedRoom.value === '' ? undefined : selectedRoom.value,
  })
}
</script>
<template>
  <div class="quotasFilters">
    <FiltersSelect
      :value="selectedYear"
      :options="years"
      label="Год"
      :disabled="loading"
      @input="(value) => selectedYear = value as unknown as number"
    />
    <FiltersSelect
      :options="months"
      label="Месяц"
      :value="selectedMonth"
      class="month"
      :disabled="loading"
      @input="(value) => selectedMonth = value as unknown as MonthNumber"
    />
    <FiltersSelect
      :options="monthsCountOptions"
      label="Выводить по"
      :value="selectedMonthsCount"
      :disabled="loading"
      @input="(value) => selectedMonthsCount = value as unknown as MonthsCount"
    />
    <FiltersSelect
      :options="availabilityOptions"
      label="Доступность"
      :value="selectedAvailabilityOption"
      allow-deselect
      :disabled="loading"
      @input="(value) => selectedAvailabilityOption = value as unknown as AvailabilityValue"
    />
    <FiltersSelect
      :options="rooms"
      label="Номер"
      :value="selectedRoom"
      allow-deselect
      :disabled="loading"
      @input="handleRoomInput"
    />
    <BootstrapButton
      label="Обновить"
      variant="outline"
      severity="primary"
      :loading="loading"
      @click="submit"
    />
  </div>
</template>
<style lang="scss" scoped>
.quotasFilters {
  display: flex;
  flex-wrap: wrap;
  gap: 1em;
  align-items: flex-end;
}

.month {
  :deep(select) {
    text-transform: capitalize;
  }
}
</style>
