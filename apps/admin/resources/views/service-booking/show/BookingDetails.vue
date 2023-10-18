<script setup lang="ts">

import { computed, defineAsyncComponent, nextTick, onMounted, shallowRef } from 'vue'

import { camelCase } from 'lodash'

import { useBookingStore } from '~resources/views/service-booking/show/store/booking'

import { useGetBookingDetailsTypesAPI } from '~api/booking/service'

import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import CardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'

import ErrorComponent from './components/ErrorComponent.vue'

const bookingStore = useBookingStore()
const booking = computed(() => bookingStore.booking)

const detailsComponent = shallowRef()

const { data: BookingDetailsTypes, execute: fetchBookingDetailsTypes } = useGetBookingDetailsTypesAPI()

const toPascalCase = (str: string): string => {
  const camelCasedStr = camelCase(str)
  return camelCasedStr.charAt(0).toUpperCase() + camelCasedStr.slice(1)
}

const setDetailsComponentByServiceType = (typeId: number | undefined) => {
  const currentServiceType = BookingDetailsTypes.value?.find((type) => type.id === typeId)
  if (currentServiceType) {
    const ComponentName = toPascalCase(currentServiceType.name)
    const DetailsComponent = defineAsyncComponent({
      loader: () => import(`./components/details/${ComponentName}.vue`),
      errorComponent: ErrorComponent,
    })
    console.log(ComponentName)
    detailsComponent.value = DetailsComponent
  } else {
    detailsComponent.value = ErrorComponent
  }
}

onMounted(async () => {
  await fetchBookingDetailsTypes()
  nextTick(() => {
    setDetailsComponentByServiceType(booking.value?.serviceType.id)
  })
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
