<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import BaseLayout from 'gts-components/Base/BaseLayout'
import LoadingSpinner from 'gts-components/Base/LoadingSpinner'
import { SelectOption } from 'gts-components/Bootstrap/lib'
import { storeToRefs } from 'pinia'

import { useHotelSearchAPI } from '~resources/vue/api/hotel/get'
import { useHotelRoomsSearchAPI } from '~resources/vue/api/hotel/rooms'

import { useCityStore } from '~stores/city'

import QuotaAvailabilityFilters from './components/QuotaAvailabilityFilters/QuotaAvailabilityFilters.vue'

import { FiltersPayload } from './components/QuotaAvailabilityFilters/lib'

const { cities } = storeToRefs(useCityStore())
const cityOptions = computed(() => {
  const options = cities.value || []
  return options.map(
    (entity) => ({ value: entity.id, label: entity.name, group: entity.country_name }),
  ) as SelectOption[]
})

const filtersPayload = ref<FiltersPayload | null>(null)
const isSearchingQuotas = ref<boolean>(false)

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
  if (value) fetchHotels()
  else hotels.value = []
})

watch(() => filtersPayload.value?.hotelIds, (value) => {
  if (value) fetchHotelsRooms()
  else hotelsRooms.value = []
})

const searhQuotas = (value: FiltersPayload) => {
  isSearchingQuotas.value = true
}

</script>

<template>
  <div id="hotel-quotas-wrapper">
    <BaseLayout>
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
          :is-submiting="isSearchingQuotas"
          @chnaged-filters-payload="(value) => {
            filtersPayload = value
          }"
          @submit="searhQuotas"
        />
        <div v-if="isSearchingQuotas" class="mt-4 d-flex justify-content-center">
          <LoadingSpinner />
        </div>
      </div>
    </BaseLayout>
  </div>
</template>
