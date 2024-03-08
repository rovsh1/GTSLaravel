<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { compareJSDate } from 'gts-common/helpers/date'
import DateRangePicker from 'gts-components/Base/DateRangePicker'
import SelectComponent from 'gts-components/Base/SelectComponent'
import BootstrapButton from 'gts-components/Bootstrap/BootstrapButton/BootstrapButton'
import { isEqual } from 'lodash'
import { DateTime } from 'luxon'
import { nanoid } from 'nanoid'

import { HotelRoom } from '~api/hotel/room'

import {
  AvailabilityValue,
  FiltersPayload,
} from './lib'

const props = withDefaults(defineProps<{
  rooms: HotelRoom[]
  loading?: MaybeRef<boolean>
}>(), {
  loading: false,
})

const emit = defineEmits<{
  (event: 'submit', value: FiltersPayload): void
  (event: 'switchRoom', value: number[]): void
  (event: 'waitSwitchRoom'): void
}>()

const defaultState = {
  dateFrom: DateTime.now().startOf('month').toJSDate(),
  dateTo: DateTime.now().endOf('month').toJSDate(),
  availability: '' as const,
}

const periodError = ref<boolean>(false)

const periodElementID = `${nanoid()}_period`

const selectedPeriod = ref<[Date, Date]>([defaultState.dateFrom, defaultState.dateTo])

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

const rooms = computed(() => props.rooms.map(({ id, name }) => ({
  value: id,
  label: name,
})))

const setDefaultRooms = () => {
  const defaultRooms = rooms.value.map((day) => day.value.toString()) as string[]
  emit('switchRoom', defaultRooms.length ? defaultRooms.map((str) => parseInt(str, 10)) : [])
  return defaultRooms
}

const selectedRoomID = ref<string[]>(setDefaultRooms())

const handleRoomInput = (value: string[]) => {
  selectedRoomID.value = value
}

const payload = computed<FiltersPayload>(() => ({
  dateFrom: selectedPeriod.value[0],
  dateTo: selectedPeriod.value[1],
  availability: selectedAvailabilityOption.value === ''
    ? null : selectedAvailabilityOption.value,
}))

const lastSubmittedPayload = ref<FiltersPayload>({ ...defaultState, availability: null })

const submit = () => {
  emit('submit', payload.value)
}

watch(payload, () => {
  if (!periodError.value) {
    submit()
  }
})

watch(rooms, () => {
  setDefaultRooms()
})

watch(selectedRoomID, () => {
  emit('waitSwitchRoom')
  setTimeout(() => {
    emit('switchRoom', selectedRoomID.value.length ? selectedRoomID.value.map((str) => parseInt(str, 10)) : [])
  }, 100)
})

const handlePeriodChanges = (dates: [Date, Date]) => {
  if (!dates[0] || !dates[1]
    || (compareJSDate(selectedPeriod.value[0], dates[0])
      && compareJSDate(selectedPeriod.value[1], dates[1]))) return
  const diffInYears = DateTime.fromJSDate(dates[1]).diff(DateTime.fromJSDate(dates[0]), 'years').years
  if (diffInYears > 1) {
    periodError.value = true
  } else {
    periodError.value = false
  }
  selectedPeriod.value = dates
}

const reset = () => {
  selectedPeriod.value = [defaultState.dateFrom, defaultState.dateTo]
  selectedAvailabilityOption.value = defaultState.availability
  selectedRoomID.value = setDefaultRooms()
  periodError.value = false
  submit()
}

const isStateChanged = computed<boolean>(() =>
  !isEqual(
    { ...payload.value, roomID: selectedRoomID.value.length },
    { ...lastSubmittedPayload.value, roomID: rooms.value.length },
  ))
</script>
<template>
  <div class="quotasFilters">
    <DateRangePicker
      :id="periodElementID"
      label="Период"
      :is-error="periodError"
      error-text="Период превышает 1 год"
      :label-outline="false"
      :value="selectedPeriod"
      :disabled="(loading as boolean)"
      @input="(dates) => handlePeriodChanges(dates as [Date, Date])"
    />
    <div class="availability-wrapper">
      <SelectComponent
        :options="availabilityOptions"
        label="Доступность"
        label-style="outline"
        :value="selectedAvailabilityOption"
        :disabled="(loading as boolean)"
        :allow-empty-item="true"
        empty-item="Не выбрано"
        :returned-empty-value="''"
        @change="(value) => {
          selectedAvailabilityOption = value.toString() as unknown as AvailabilityValue
        }"
      />
    </div>

    <div class="quotasFilters-rooms">
      <SelectComponent
        :options="rooms"
        label="Выберите номер(а)"
        label-style="outline"
        :value="selectedRoomID"
        required
        multiple
        placeholder="Не выбрано"
        @change="(value) => {
          handleRoomInput(value)
        }"
      />
      <div v-if="loading" class="quotasFilters-rooms-disable" />
    </div>
    <div class="actions">
      <BootstrapButton
        label="Сбросить"
        only-icon="filter_alt_off"
        variant="outline"
        severity="link"
        :disabled="loading || !isStateChanged"
        @click="reset"
      />
    </div>
  </div>
</template>
<style lang="scss">
%flow {
  display: flex;
  gap: 1em;
}

.quotasFilters {
  @extend %flow;

  flex-wrap: wrap;
  align-items: flex-start;

  .quotasFilters-rooms {
    position: relative;
    width: 245px;
  }

  input {
    height: 34px;
  }
}

.actions {
  @extend %flow;
}

.month {
  :deep(select) {
    text-transform: capitalize;
  }
}

.quotasFilters-rooms-disable {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: var(--bs-border-radius);
  background-color: var(--bs-secondary-bg);
  opacity: 0.4;
}

.availability-wrapper {
  min-width: 9.375rem;
}
</style>
