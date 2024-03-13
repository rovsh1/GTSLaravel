<script lang="ts" setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'

import { compareJSDate } from 'gts-common/helpers/date'
import DateRangePicker from 'gts-components/Base/DateRangePicker'
import OverlayLoading from 'gts-components/Base/OverlayLoading'
import SelectComponent from 'gts-components/Base/SelectComponent'
import BootstrapButton from 'gts-components/Bootstrap/BootstrapButton/BootstrapButton'
import { SelectOption } from 'gts-components/Bootstrap/lib'
import { isEqual } from 'lodash'
import { DateTime } from 'luxon'
import { nanoid } from 'nanoid'

import { AvailabilityValue } from '~resources/views/hotel/quotas/components/QuotasFilters/lib'

import {
  defaultFiltersPayload,
  FiltersPayload,
} from './lib'

withDefaults(defineProps<{
  cities: SelectOption[]
  hotels: SelectOption[]
  rooms: SelectOption[]
  roomsTypes: SelectOption[]
  availabilitys: SelectOption[]
  isCitiesFetch?: boolean
  isHotelsFetch?: boolean
  isRoomsFetch?: boolean
  isRoomsTypesFetch?: boolean
  isSubmiting?: boolean
}>(), {
  isCitiesFetch: false,
  isHotelsFetch: false,
  isRoomsFetch: false,
  isSubmiting: false,
  isRoomsTypesFetch: false,
})

const emit = defineEmits<{
  (event: 'chnagedFiltersPayload', value: FiltersPayload): void
  (event: 'submit'): void
  (event: 'reset'): void
}>()

const periodError = ref<boolean>(false)
const isFirstInitializingCities = ref<boolean>(true)
const isFirstInitializingHotels = ref<boolean>(true)

const periodElementID = `${nanoid()}_period`

const filtersPayload = reactive<FiltersPayload>({
  dateFrom: defaultFiltersPayload.dateFrom,
  dateTo: defaultFiltersPayload.dateTo,
  cityIds: defaultFiltersPayload.cityIds,
  hotelIds: defaultFiltersPayload.hotelIds,
  roomIds: defaultFiltersPayload.roomIds,
  roomTypeIds: defaultFiltersPayload.roomTypeIds,
  availability: defaultFiltersPayload.availability,
})

watch(filtersPayload, () => {
  emit('chnagedFiltersPayload', filtersPayload)
})

watch(() => filtersPayload.cityIds, () => {
  if (!isFirstInitializingCities.value) {
    filtersPayload.hotelIds = []
    filtersPayload.roomIds = []
    filtersPayload.roomTypeIds = []
  } else {
    isFirstInitializingCities.value = false
  }
})

watch(() => filtersPayload.hotelIds, (value) => {
  if (!isFirstInitializingHotels.value) {
    filtersPayload.roomIds = []
    filtersPayload.roomTypeIds = []
  } else {
    isFirstInitializingHotels.value = false
    if (value.length) filtersPayload.roomTypeIds = []
  }
})

const convertValuesToNumbers = (values: any): number[] => {
  if (values.length === 0) {
    return []
  }
  const resultArray = values.map(Number)
  return resultArray
}

const handlePeriodChanges = (dates: [Date, Date]) => {
  if (!dates[0] || !dates[1]
    || (compareJSDate(filtersPayload.dateFrom, dates[0])
      && compareJSDate(filtersPayload.dateTo, dates[1]))) return
  const diffInYears = DateTime.fromJSDate(dates[1]).diff(DateTime.fromJSDate(dates[0]), 'years').years
  if (diffInYears > 1) {
    periodError.value = true
  } else {
    periodError.value = false
  }
  const [dateFrom, dateTo] = dates
  filtersPayload.dateFrom = dateFrom
  filtersPayload.dateTo = dateTo
}

const setFiltersFromUrlParameters = () => {
  const urlParams = new URLSearchParams(window.location.search)
  const periodParameter = urlParams.get('period')
  const citiesParameter = urlParams.get('cities')
  const hotelsParameter = urlParams.get('hotels')
  const roomsParameter = urlParams.get('rooms')
  const roomsTypesParameter = urlParams.get('room-types')
  const availabilityParameter = urlParams.get('availability')
  if (citiesParameter) filtersPayload.cityIds = convertValuesToNumbers(citiesParameter.split(','))
  if (hotelsParameter) filtersPayload.hotelIds = convertValuesToNumbers(hotelsParameter.split(','))
  if (roomsParameter) filtersPayload.roomIds = convertValuesToNumbers(roomsParameter.split(','))
  if (roomsTypesParameter) filtersPayload.roomTypeIds = convertValuesToNumbers(roomsTypesParameter.split(','))
  if (availabilityParameter) filtersPayload.availability = availabilityParameter?.trim().toLocaleLowerCase() as AvailabilityValue
  if (periodParameter) {
    const [dateFromParametr, dateToParametr] = periodParameter.split('-')
    const dateFrom = DateTime.fromFormat(dateFromParametr, 'dd.MM.yyyy')
    const dateTo = DateTime.fromFormat(dateToParametr, 'dd.MM.yyyy')
    if (dateFrom.isValid && dateTo.isValid) {
      filtersPayload.dateFrom = dateFrom.toJSDate()
      filtersPayload.dateTo = dateTo.toJSDate()
    }
  }
  if (urlParams.size) emit('submit')
}

const isFilterStateChanged = computed<boolean>(() =>
  !isEqual(defaultFiltersPayload, filtersPayload))

const resetFilters = () => {
  filtersPayload.dateFrom = defaultFiltersPayload.dateFrom
  filtersPayload.dateTo = defaultFiltersPayload.dateTo
  filtersPayload.cityIds = defaultFiltersPayload.cityIds
  filtersPayload.hotelIds = defaultFiltersPayload.hotelIds
  filtersPayload.roomIds = defaultFiltersPayload.roomIds
  filtersPayload.roomTypeIds = defaultFiltersPayload.roomTypeIds
  filtersPayload.availability = defaultFiltersPayload.availability
  emit('reset')
}

onMounted(() => {
  emit('chnagedFiltersPayload', filtersPayload)
  setFiltersFromUrlParameters()
})
</script>
<template>
  <div class="quotaAvailabilityFilters">
    <DateRangePicker
      :id="periodElementID"
      label="Период"
      :is-error="periodError"
      error-text="Период превышает 1 год"
      :label-outline="false"
      :value="[filtersPayload.dateFrom, filtersPayload.dateTo]"
      required
      :disabled="isSubmiting"
      @input="(dates) => handlePeriodChanges(dates as [Date, Date])"
    />
    <div class="quotaAvailabilityFilters-field">
      <SelectComponent
        :options="cities"
        label="Выберите город(а)"
        disabled-placeholder="Не выбрано"
        label-style="outline"
        :value="filtersPayload.cityIds"
        multiple
        placeholder="Не выбрано"
        :disabled="isSubmiting"
        @change="(value) => {
          filtersPayload.cityIds = convertValuesToNumbers(value)
        }"
      />
      <OverlayLoading v-if="isCitiesFetch" />
    </div>
    <div class="quotaAvailabilityFilters-field">
      <SelectComponent
        :options="hotels"
        label="Выберите отель(и)"
        label-style="outline"
        :disabled="isSubmiting"
        disabled-placeholder="Не выбрано"
        :value="filtersPayload.hotelIds"
        multiple
        placeholder="Не выбрано"
        @change="(value) => filtersPayload.hotelIds = convertValuesToNumbers(value)"
      />
      <OverlayLoading v-if="isHotelsFetch" />
    </div>
    <div v-show="filtersPayload.hotelIds.length" class="quotaAvailabilityFilters-field">
      <SelectComponent
        :options="rooms"
        label="Выберите номер(а)"
        label-style="outline"
        :disabled="!filtersPayload.hotelIds.length || isSubmiting"
        :disabled-placeholder="filtersPayload.hotelIds.length ? 'Не выбрано' : 'Выберите отель(и)'"
        :value="filtersPayload.roomIds"
        multiple
        placeholder="Не выбрано"
        @change="(value) => filtersPayload.roomIds = convertValuesToNumbers(value)"
      />
      <OverlayLoading v-if="isRoomsFetch" />
    </div>
    <div v-show="!filtersPayload.hotelIds.length" class="quotaAvailabilityFilters-field">
      <SelectComponent
        :options="roomsTypes"
        label="Выберите тип номера(ов)"
        label-style="outline"
        :disabled="!!filtersPayload.hotelIds.length || isSubmiting"
        disabled-placeholder="Не выбрано"
        :value="filtersPayload.roomTypeIds"
        multiple
        placeholder="Не выбрано"
        @change="(value) => filtersPayload.roomTypeIds = convertValuesToNumbers(value)"
      />
      <OverlayLoading v-if="isRoomsTypesFetch" />
    </div>
    <div class="quotaAvailabilityFilters-field">
      <SelectComponent
        :options="availabilitys"
        label="Доступность"
        label-style="outline"
        :value="filtersPayload.availability"
        :disabled="isSubmiting"
        :allow-empty-item="true"
        empty-item="Не выбрано"
        :returned-empty-value="null"
        @change="(value) => {
          filtersPayload.availability = value as AvailabilityValue || null
        }"
      />
    </div>
    <div class="actions">
      <BootstrapButton
        label="Поиск"
        :disabled="isCitiesFetch || isHotelsFetch || isRoomsFetch || isRoomsTypesFetch || isSubmiting || periodError"
        severity="primary"
        @click="emit('submit')"
      />
      <BootstrapButton
        label="Сбросить"
        only-icon="filter_alt_off"
        variant="outline"
        severity="link"
        :disabled="isCitiesFetch || isHotelsFetch || isRoomsFetch || isRoomsTypesFetch || isSubmiting || !isFilterStateChanged"
        @click="resetFilters"
      />
    </div>
  </div>
</template>
<style lang="scss">
%flow {
  display: flex;
  gap: 1em;
}

.quotaAvailabilityFilters {
  @extend %flow;

  flex-wrap: wrap;
  align-items: flex-start;

  .quotaAvailabilityFilters-field {
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

.quotaAvailabilityFilters-disable {
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
