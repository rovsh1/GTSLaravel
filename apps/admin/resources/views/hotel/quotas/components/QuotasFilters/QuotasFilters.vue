<script lang="ts" setup>
import { computed, ref } from 'vue'

import backspaceIcon from '@mdi/svg/svg/backspace.svg'
import { MaybeRef } from '@vueuse/core'
import { isEqual } from 'lodash'
import { DateTime } from 'luxon'

import { HotelRoomID } from '~api/hotel'
import { MonthNumber } from '~api/hotel/quotas/list'
import { HotelRoom } from '~api/hotel/room'

import { getEachMonthInYear } from '~lib/date'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import CompactSelect from '~components/Bootstrap/CompactSelect.vue'

import {
  AvailabilityValue,
  createYear,
  defaultFiltersPayload,
  FiltersPayload,
  Month,
  MonthsCount,
  Year,
} from './lib'

const props = withDefaults(defineProps<{
  rooms: HotelRoom[]
  loading?: MaybeRef<boolean>
}>(), {
  loading: false,
})

const emit = defineEmits<{
  (event: 'submit', value: FiltersPayload): void
}>()

const defaultState = {
  year: defaultFiltersPayload.year,
  month: defaultFiltersPayload.month,
  monthsCount: defaultFiltersPayload.monthsCount,
  availability: '' as const,
  roomID: '' as const,
}

const selectedYear = ref<Year['value']>(defaultState.year)

const yearsAddToCurrent = 5

const years = computed<Year[]>(() => [
  ...Array.from({ length: yearsAddToCurrent })
    .map((item, index) => createYear(defaultState.year - index)).reverse(),
  ...Array.from({ length: yearsAddToCurrent - 1 })
    .map((item, index) => createYear(defaultState.year + 1 + index)),
])

const months = computed<Month[]>(() => {
  const year = DateTime.fromFormat(selectedYear.value.toString(), 'yyyy').toJSDate()
  return getEachMonthInYear(year).map((month) => ({
    label: month.toFormat('LLLL'),
    value: Number(month.toFormat('L')) as MonthNumber,
  }))
})

const selectedMonth = ref<Month['value']>(defaultState.month)

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

const selectedMonthsCount = ref<MonthsCountOption['value']>(defaultState.monthsCount)

type AvailabilityOption = {
  value: AvailabilityValue
  label: string
}

const availabilityOptions: AvailabilityOption[] = [
  { value: 'sold', label: 'Проданные' },
  { value: 'stopped', label: 'Остановленные' },
  { value: 'available', label: 'Доступные' },
]

const selectedAvailabilityOption = ref<AvailabilityOption['value'] | ''>(defaultState.availability)

const rooms = computed(() => props.rooms.map(({ id, name, customName }) => ({
  value: id,
  label: `${name} (${customName})`,
})))

const selectedRoomID = ref<HotelRoomID | ''>(defaultState.roomID)

const handleRoomInput = (value: string | number) => {
  const numericValue = Number(value)
  if (value === '' || Number.isNaN(numericValue)) {
    selectedRoomID.value = ''
  } else {
    selectedRoomID.value = numericValue
  }
}

const payload = computed<FiltersPayload>(() => ({
  year: selectedYear.value,
  month: selectedMonth.value,
  monthsCount: selectedMonthsCount.value,
  availability: selectedAvailabilityOption.value === ''
    ? null : selectedAvailabilityOption.value,
  roomID: selectedRoomID.value === '' ? null : selectedRoomID.value,
}))

const lastSubmittedPayload = ref<FiltersPayload>(defaultFiltersPayload)

const submit = () => {
  emit('submit', payload.value)
  lastSubmittedPayload.value = payload.value
}

const reset = () => {
  selectedYear.value = defaultState.year
  selectedMonth.value = defaultState.month
  selectedMonthsCount.value = defaultState.monthsCount
  selectedAvailabilityOption.value = defaultState.availability
  selectedRoomID.value = defaultState.roomID
  submit()
}

const isStateChanged = computed<boolean>(() =>
  !isEqual(payload.value, lastSubmittedPayload.value))
</script>
<template>
  <div class="quotasFilters">
    <CompactSelect
      :value="selectedYear"
      :options="years"
      label="Год"
      :disabled="loading"
      @input="(value) => selectedYear = Number(value)"
    />
    <CompactSelect
      :options="months"
      label="Месяц"
      :value="selectedMonth"
      class="month"
      :disabled="loading"
      @input="(value) => selectedMonth = Number(value) as unknown as MonthNumber"
    />
    <CompactSelect
      :options="monthsCountOptions"
      label="Выводить по"
      :value="selectedMonthsCount"
      :disabled="loading"
      @input="(value) => selectedMonthsCount = Number(value) as unknown as MonthsCount"
    />
    <CompactSelect
      :options="availabilityOptions"
      label="Доступность"
      :value="selectedAvailabilityOption"
      allow-deselect
      :disabled="loading"
      @input="(value) => {
        selectedAvailabilityOption = value.toString() as unknown as AvailabilityValue
      }"
    />
    <CompactSelect
      :options="rooms"
      label="Номер"
      :value="selectedRoomID"
      allow-deselect
      :disabled="loading"
      @input="handleRoomInput"
    />
    <div class="actions">
      <BootstrapButton
        label="Обновить"
        variant="outline"
        severity="primary"
        :loading="loading"
        :disabled="!isStateChanged"
        @click="submit"
      />
      <BootstrapButton
        label="Сбросить"
        :only-icon="backspaceIcon"
        variant="outline"
        severity="link"
        :disabled="loading || !isStateChanged"
        @click="reset"
      />
    </div>
  </div>
</template>
<style lang="scss" scoped>
%flow {
  display: flex;
  gap: 1em;
}

.quotasFilters {
  @extend %flow;

  flex-wrap: wrap;
  align-items: flex-end;
}

.actions {
  @extend %flow;
}

.month {
  :deep(select) {
    text-transform: capitalize;
  }
}
</style>
