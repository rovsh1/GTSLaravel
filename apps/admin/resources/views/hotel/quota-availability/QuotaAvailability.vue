<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import { formatDateToAPIDate } from 'gts-common/helpers/date'
import BaseLayout from 'gts-components/Base/BaseLayout'
import EmptyData from 'gts-components/Base/EmptyData'
import LoadingSpinner from 'gts-components/Base/LoadingSpinner'
import { SelectOption } from 'gts-components/Bootstrap/lib'
import { storeToRefs } from 'pinia'

import { Day, Month } from '~resources/views/hotel/quotas/components/lib'
import { useHotelSearchAPI } from '~resources/vue/api/hotel/get'
import { useQuotaAvailability } from '~resources/vue/api/hotel/quotas/availability'
import { useHotelRoomsSearchAPI } from '~resources/vue/api/hotel/rooms'

import { useCityStore } from '~stores/city'

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

const filtersPayload = ref<FiltersPayload | null>(null)
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
} = useHotelRoomsSearchAPI(computed(() => ({ hotelIds: filtersPayload.value?.hotelIds || undefined })))

const {
  execute: fetchQuotaAvailability,
  data: quotaAvailability,
  isFetching: isFetchingQuotaAvailability,
} = useQuotaAvailability(computed(() => {
  if (!filtersPayload.value) return null
  const { dateFrom, dateTo, cityIds, hotelIds, roomIds } = filtersPayload.value
  return {
    dateFrom: formatDateToAPIDate(dateFrom),
    dateTo: formatDateToAPIDate(dateTo),
    cityIds: cityIds || [],
    hotelIds: hotelIds || [],
    roomIds: roomIds || [],
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

watch(() => filtersPayload.value?.cityIds, (value) => {
  if (value?.length) fetchHotels()
  else hotels.value = []
})

watch(() => filtersPayload.value?.hotelIds, (value) => {
  if (value?.length) fetchHotelsRooms()
  else hotelsRooms.value = []
})

const searhQuotas = async () => {
  if (filtersPayload.value) {
    await fetchQuotaAvailability()
    const quotasAccumalationData = getQuotasPeriod({
      filters: {
        dateFrom: filtersPayload.value.dateFrom,
        dateTo: filtersPayload.value.dateTo,
      },
    })
    const { period, months } = quotasAccumalationData
    quotasPeriod.value = period
    quotasPeriodMonths.value = months
  }
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
          :is-hotels-fetch="isFetchingHotels"
          :is-rooms-fetch="isFetchingHotelsRooms"
          :is-submiting="isFetchingQuotaAvailability"
          @chnaged-filters-payload="(value) => {
            filtersPayload = value
          }"
          @submit="searhQuotas"
        />
        <div v-if="isFetchingQuotaAvailability" class="mt-4 d-flex justify-content-center">
          <LoadingSpinner />
        </div>
        <template v-else-if="quotaAvailability">
          <HotelQuotas
            v-if="quotaAvailability.length"
            class="mt-4"
            :months="quotasPeriodMonths"
            :days="quotasPeriod"
            :hotels-quotas="quotaAvailability"
          />
          <EmptyData v-else class="mt-4">
            Квоты по выбранным параметрам отсутствуют
          </EmptyData>
        </template>
      </div>
    </BaseLayout>
  </div>
</template>
