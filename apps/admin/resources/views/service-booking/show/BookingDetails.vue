<script setup lang="ts">

import { computed, defineAsyncComponent, shallowRef } from 'vue'

import { watchOnce } from '@vueuse/core'

import { useBookingStore } from '~resources/views/service-booking/show/store/booking'

import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import CardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'

const bookingStore = useBookingStore()
const booking = computed(() => bookingStore.booking)

const TransferToAirportDetails = defineAsyncComponent(() => import('./components/details/TransferToAirport.vue'))
const TransferFromAirportDetails = defineAsyncComponent(() => import('./components/details/TransferFromAirport.vue'))
const detailsComponent = shallowRef()

// @todo скорее всего нужно получать с бекенда
enum ServiceTypeEnum {
  HOTEL_BOOKING = 1,
  CIP_IN_AIRPORT = 2,
  CAR_RENT = 3,
  TRANSFER_TO_RAILWAY = 4,
  TRANSFER_FROM_RAILWAY = 5,
  TRANSFER_TO_AIRPORT = 6,
  TRANSFER_FROM_AIRPORT = 7,
  OTHER = 8,
}

// @todo продумать как устанавливать компонент деталей
watchOnce(booking, () => {
  switch (booking.value?.serviceType.id) {
    case ServiceTypeEnum.TRANSFER_TO_AIRPORT:
      detailsComponent.value = TransferToAirportDetails
      break
    case ServiceTypeEnum.TRANSFER_FROM_AIRPORT:
      detailsComponent.value = TransferFromAirportDetails
      break
    default:
      throw Error('Unknown booking type')
  }
})

</script>

<template>
  <Card>
    <CardTitle class="mr-4" title="Информация о брони" />
    <component :is="detailsComponent" v-if="detailsComponent" />
  </Card>
</template>

<style lang="scss" scoped>
.total-sum {
  margin-bottom: 0.5rem;
}
</style>
