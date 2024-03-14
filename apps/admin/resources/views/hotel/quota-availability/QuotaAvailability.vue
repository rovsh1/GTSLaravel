<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import { formatDateToAPIDate } from 'gts-common/helpers/date'
import BaseLayout from 'gts-components/Base/BaseLayout'
import EmptyData from 'gts-components/Base/EmptyData'
import LoadingSpinner from 'gts-components/Base/LoadingSpinner'
import { SelectOption } from 'gts-components/Bootstrap/lib'
import { DateTime } from 'luxon'
import { storeToRefs } from 'pinia'

import { availabilityOptions, AvailabilityValue, Day, Month } from '~resources/views/hotel/quotas/components/lib/types'
import { useHotelSearchAPI } from '~resources/vue/api/hotel/get'
import { useQuotaAvailability } from '~resources/vue/api/hotel/quotas/availability'
import { useHotelRoomsSearchAPI } from '~resources/vue/api/hotel/rooms'

import { useCityStore } from '~stores/city'
import { useRoomTypesStore } from '~stores/room-types'

import HotelQuotas from './components/HotelQuotas.vue'
import QuotaAvailabilityFilters from './components/QuotaAvailabilityFilters/QuotaAvailabilityFilters.vue'

import { getQuotasPeriod } from './components/lib'
import { FiltersPayload } from './components/QuotaAvailabilityFilters/lib'

const { cities } = storeToRefs(useCityStore())
const cityOptions = computed(() => {
  const options = cities.value || []
  return options.map(
    (entity) => ({ value: entity.id, label: entity.name, group: entity.country_name }),
  ) as SelectOption[]
})

const { roomTypes } = storeToRefs(useRoomTypesStore())
const roomTypesOptions = computed(() => {
  const options = roomTypes.value || []
  return options.map(
    (entity) => ({ value: entity.id, label: entity.name }),
  ) as SelectOption[]
})

const availabilitysOptions = computed(() => {
  const options = availabilityOptions || []
  return options.map(
    (entity) => ({ value: entity.value, label: entity.label }),
  ) as SelectOption[]
})

const filtersPayload = ref<FiltersPayload | null>(null)
const preSendFiltersPayload = ref<FiltersPayload | null>(null)
const quotasPeriod = ref<Day[]>([])
const quotasPeriodMonths = ref<Month[]>([])

const {
  execute: fetchHotels,
  data: hotels,
  isFetching: isFetchingHotels,
} = useHotelSearchAPI(computed(() => ({ cityIds: filtersPayload.value?.cityIds || undefined })))

const {
  execute: fetchHotelsRooms,
  data: hotelsRooms,
  isFetching: isFetchingHotelsRooms,
} = useHotelRoomsSearchAPI(computed(() => ({ hotel_ids: filtersPayload.value?.hotelIds || undefined })))

const {
  execute: fetchQuotaAvailability,
  data: quotaAvailability,
  isFetching: isFetchingQuotaAvailability,
} = useQuotaAvailability(computed(() => {
  if (!filtersPayload.value) return null
  const { dateFrom, dateTo, cityIds, hotelIds, roomIds, roomTypeIds, availability } = filtersPayload.value
  return {
    dateFrom: formatDateToAPIDate(dateFrom),
    dateTo: formatDateToAPIDate(dateTo),
    cityIds: cityIds || [],
    hotelIds: hotelIds || [],
    roomIds: roomIds || [],
    roomTypeIds: roomTypeIds || [],
    availability,
  }
}))

const getHotelNameById = (hotelId: number) => hotels.value?.find((hotel) => hotel.id === hotelId)?.name || ''

const hotelsOptions = computed(() => {
  const options = hotels.value || []
  return options.map(
    (entity) => ({ value: entity.id, label: entity.name }),
  ) as SelectOption[]
})

const hotelsRoomsOptions = computed(() => {
  const options = hotelsRooms.value || []
  return options.map(
    (entity) => ({ value: entity.id, label: `${entity.name} (${getHotelNameById(entity.hotelID)})` }),
  ) as SelectOption[]
})

watch(() => filtersPayload.value?.cityIds, () => {
  fetchHotels()
})

watch(() => filtersPayload.value?.hotelIds, (value) => {
  if (value?.length) fetchHotelsRooms()
  else hotelsRooms.value = []
})

const setUrlParameters = (filters: FiltersPayload) => {
  const periodParameterFrom = DateTime.fromJSDate(filters.dateFrom).toFormat('dd.MM.yyyy')
  const periodParameterTo = DateTime.fromJSDate(filters.dateTo).toFormat('dd.MM.yyyy')
  const periodParameter = `${periodParameterFrom}-${periodParameterTo}`
  const citiesParametr = filters.cityIds.length ? filters.cityIds.join(',') : null
  const hotelsParametr = filters.hotelIds.length ? filters.hotelIds.join(',') : null
  const roomsParametr = filters.roomIds.length ? filters.roomIds.join(',') : null
  const roomTypesParametr = filters.roomTypeIds.length ? filters.roomTypeIds.join(',') : null
  const availabilityParametr = filters.availability
  const params = {
    'period': encodeURIComponent(periodParameter),
    'cities': citiesParametr,
    'hotels': hotelsParametr,
    'rooms': roomsParametr,
    'room-types': roomTypesParametr,
    'availability': availabilityParametr,
  }
  const validParams = Object.entries(params)
    .filter(([_key, value]) => value !== null)
    .map(([key, value]) => `${key}=${value}`)
    .join('&')
  const currentUrl = window.location.pathname
  const separator = (currentUrl.indexOf('?') > -1) ? '&' : '?'
  const newUrl = currentUrl + separator + validParams
  window.history.replaceState({}, document.title, newUrl)
}

const searhQuotas = async () => {
  if (filtersPayload.value) {
    const validAvailability = availabilitysOptions.value.find((availabilityOption) =>
      availabilityOption.value === filtersPayload.value?.availability)
    filtersPayload.value.availability = validAvailability?.value as AvailabilityValue || null
    await fetchQuotaAvailability()
    const quotasAccumalationData = getQuotasPeriod({
      filters: {
        dateFrom: filtersPayload.value.dateFrom,
        dateTo: filtersPayload.value.dateTo,
      },
    })
    preSendFiltersPayload.value = { ...filtersPayload.value }
    const { period, months } = quotasAccumalationData
    quotasPeriod.value = period
    quotasPeriodMonths.value = months
    setUrlParameters(filtersPayload.value)
  }
}

const resetQuotasData = () => {
  quotaAvailability.value = null
  const currentUrl = window.location.pathname
  window.history.replaceState({}, document.title, currentUrl)
}

</script>

<template>
  <div id="hotel-quotas-wrapper">
    <BaseLayout style="justify-content: start;">
      <template #title>
        <div class="title">Доступность</div>
      </template>
      <div class="quotasBody">
        <QuotaAvailabilityFilters
          :cities="cityOptions"
          :hotels="hotelsOptions"
          :rooms="hotelsRoomsOptions"
          :rooms-types="roomTypesOptions"
          :availabilitys="availabilitysOptions"
          :is-hotels-fetch="isFetchingHotels"
          :is-rooms-fetch="isFetchingHotelsRooms"
          :is-submiting="isFetchingQuotaAvailability"
          @chnaged-filters-payload="(value) => {
            filtersPayload = value
          }"
          @submit="searhQuotas"
          @reset="resetQuotasData"
        />
        <div v-if="isFetchingQuotaAvailability" class="mt-4 d-flex justify-content-center">
          <LoadingSpinner />
        </div>
        <template v-else-if="quotaAvailability">
          <HotelQuotas
            v-if="quotaAvailability.length && preSendFiltersPayload"
            class="mt-4"
            :months="quotasPeriodMonths"
            :days="quotasPeriod"
            :hotels-quotas="quotaAvailability"
            :filters="preSendFiltersPayload"
          />
          <EmptyData v-else class="mt-4">
            Квоты по выбранным параметрам отсутствуют
          </EmptyData>
        </template>
      </div>
    </BaseLayout>
  </div>
</template>
