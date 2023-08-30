<script lang="ts" setup>

import { onMounted, ref } from 'vue'

import { z } from 'zod'

import { useRoomSeasonsPricesListAPI } from '~resources/api/hotel/prices/seasons'

import { requestInitialData } from '~lib/initial-data'

import BootstrapCard from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'

import HotelPricesTable from './components/HotelPricesTable.vue'

const { hotelID, rooms, seasons } = requestInitialData(
  'view-initial-data-hotel-prices',
  z.object({
    hotelID: z.number(),
    rooms: z.array(z.object({
      id: z.number(),
      hotel_id: z.number(),
      type_id: z.number(),
      rooms_number: z.number(),
      guests_count: z.number(),
      type_name: z.string(),
      name: z.string().nullable(),
      price_rates: z.array(z.object({
        id: z.number(),
        hotel_id: z.number(),
        name: z.string(),
        description: z.string(),
      })),
    })),
    seasons: z.array(z.object({
      id: z.number(),
      contract_id: z.number(),
      name: z.string(),
      date_start: z.string(),
      date_end: z.string(),
    })),
  }),
)

const closeAllButParam = ref<string | undefined>()

const { data: prices, isFetching: pricesLoad, execute: fetchPrices } = useRoomSeasonsPricesListAPI({ hotelID })

onMounted(async () => {
  await fetchPrices()
})
</script>
<template>
  <BootstrapCard v-for="room in rooms" :key="room.id">
    <template #title>
      <h2>{{ room.name }} ({{ room.type_name }})</h2>
    </template>
    <HotelPricesTable
      :hotel-id="hotelID"
      :room-data="room"
      :seasons-data="seasons"
      :prices-data="prices"
      :is-fetching="pricesLoad"
      :close-all-but="closeAllButParam"
      @close-all="but => closeAllButParam = but"
      @update-data="fetchPrices"
    />
  </BootstrapCard>
</template>

<style lang="scss" scoped>
@use "~resources/sass/vendor/bootstrap/configuration" as bs;
</style>
