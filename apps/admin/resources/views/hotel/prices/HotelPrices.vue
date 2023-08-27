<script lang="ts" setup>

import { ref } from 'vue'

import { z } from 'zod'

import { requestInitialData } from '~lib/initial-data'

import BootstrapCard from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'

import HotelPricesTable from './components/HotelPricesTable.vue'
import SeasonEditPrice from './components/SeasonEditPrice.vue'
import SeasonPriceDaysTable from './components/SeasonPriceDaysTable.vue'

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
console.log('hotelId:', hotelID)
console.log('rooms:', rooms)
console.log('seasons:', seasons)

// const prices = computed(() => useHotelPricesListAPI({ hotelID }));

// const b =computed(()=>useHotelPricesListAPI({ hotelID }))

// async function fetchData() {
//   try {
//     console.log("test test")
//    const res= useHotelPricesListAPI({ hotelID })
//    console.log("-------------res------------:",res)
//   } catch (error) {
//     console.error(error)
//   }
// }

// onMounted(fetchData)

// watchEffect(() => {
//   console.log(fetchData())
// })

// watch(filtersPayload, () => fetchHotelPrices())

const editable = ref(false)
</script>
<template>
  <BootstrapCard>
    <template #title>
      <h2>Четырехместный (170)</h2>
    </template>
    <HotelPricesTable
      :data="[{
        id: 1,
        value: 10000,
      }, {
        id: 2,
        value: 10001,
      }, {
        id: 3,
        value: 10002,
      }, {
        id: 4,
        value: 10003,
      }]"
      @show-season="editable = !editable"
    />
    <SeasonEditPrice v-if="editable" />
    <SeasonPriceDaysTable
      v-if="editable"
      :data="[{
        id: 1,
        value: 10000,
      }, {
        id: 2,
        value: 10001,
      }, {
        id: 3,
        value: 10002,
      }, {
        id: 4,
        value: 10003,
      }]"
    />
  </BootstrapCard>
</template>

<style lang="scss" scoped>
@use "~resources/sass/vendor/bootstrap/configuration" as bs;
</style>
