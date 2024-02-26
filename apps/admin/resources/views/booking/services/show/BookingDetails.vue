<script setup lang="ts">

import { computed, defineAsyncComponent, nextTick, shallowRef } from 'vue'

import { watchOnce } from '@vueuse/core'
import { toPascalCase } from 'gts-common/helpers/strings'

import ErrorComponent from '~resources/views/booking/shared/components/ErrorComponent.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { useGetBookingDetailsTypesAPI } from '~api/booking/service'

import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import CardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'

const bookingStore = useBookingStore()
const booking = computed(() => bookingStore.booking)

const detailsComponent = shallowRef()

const { data: BookingDetailsTypes, execute: fetchBookingDetailsTypes } = useGetBookingDetailsTypesAPI()

const setDetailsComponentByServiceType = (typeId: number | undefined) => {
  const currentServiceType = BookingDetailsTypes.value?.find((type) => type.id === typeId)
  if (!currentServiceType) {
    detailsComponent.value = ErrorComponent
    return
  }
  const ComponentName = toPascalCase(currentServiceType.system_name)
  detailsComponent.value = defineAsyncComponent({
    loader: () => import(`./components/details/${ComponentName}.vue`),
    errorComponent: ErrorComponent,
  })
}

watchOnce(booking, async () => {
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
