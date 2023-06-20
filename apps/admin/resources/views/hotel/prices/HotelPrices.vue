<script lang="ts" setup>

import { z } from 'zod'

import { requestInitialData } from '~lib/initial-data'

import BaseLayout from '~components/BaseLayout.vue'

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
  <BaseLayout>
    <!-- <ul class="list">
      <li v-for="price in prices">{{ price }}</li>
    </ul> -->
  </BaseLayout>
</template>
<style lang="scss" scoped>
@use "~resources/sass/vendor/bootstrap/configuration" as bs;

.title {
  padding-left: calc(#{bs.$form-select-padding-x} - 0.1em);
}

.quotasBody {
  display: flex;
  flex-flow: column;
  gap: 2em;
}

.quotasTables {
  display: flex;
  flex-flow: column;
  gap: 2em;
}

.rooms-cards {
  display: flex;
  flex-flow: column wrap;
  gap: 32px;
}
</style>
