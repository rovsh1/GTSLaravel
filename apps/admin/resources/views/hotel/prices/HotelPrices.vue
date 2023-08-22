<script lang="ts" setup>

import { z } from 'zod'

import { requestInitialData } from '~lib/initial-data'

import RoomPriceCard from './components/RoomPriceCard.vue'

const { hotelID } = requestInitialData(
  'view-initial-data-hotel-prices',
  z.object({
    hotelID: z.number(),
  }),
)
console.log('hotelId:', hotelID)

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

// const editable = ref(false)
</script>
<template>
  <RoomPriceCard title="Четырехместный (170)" :data="{}" />
  <RoomPriceCard title="Двухместный (90)" :data="{}" />
</template>

<style lang="scss" scoped>
@use "~resources/sass/vendor/bootstrap/configuration" as bs;

</style>
