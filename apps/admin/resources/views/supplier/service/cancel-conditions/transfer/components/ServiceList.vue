<script setup lang="ts">

import { computed } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import { useQuickSearch } from '~resources/composables/quick-search'

import { Car, Season } from '~api/models'

import { requestInitialData } from '~lib/initial-data'

import CancelConditionsModal from './CancelConditionsModal.vue'
import ServicePricesBlock from './ServicePricesBlock.vue'

import { useCancelConditions } from '../composables/cancel-conditions'

const { seasons, services, cars, supplierId } = requestInitialData('view-initial-data-supplier', z.object({
  supplierId: z.number(),
  seasons: z.array(z.object({
    id: z.number(),
    number: z.string(),
    supplier_id: z.number(),
    date_start: z.string(),
    date_end: z.string(),
  })),
  services: z.array(z.object({
    id: z.number(),
    supplier_id: z.number(),
    title: z.string(),
    type: z.number(),
  })),
  cars: z.array(z.object({
    id: z.number(),
    mark: z.string(),
    model: z.string(),
  })),
}))

const { quickSearch } = useQuickSearch()

const filteredServices = computed(() => {
  if (quickSearch && quickSearch.trim().length > 0) {
    return services.filter((service) => service.title.toLowerCase().includes(quickSearch.toLowerCase()))
  }
  return services
})

const [isModalOpened, toggleModal] = useToggle()

const { cancelConditions, load, save, isLoading } = useCancelConditions()

const handleEdit = (serviceId: number, seasonId: number, carId: number): void => {
  toggleModal(true)
  load(supplierId, serviceId, seasonId, carId)
}

const handleSave = async (): Promise<void> => {
  await save()
  toggleModal(false)
}

</script>

<template>
  <CancelConditionsModal
    v-model="cancelConditions"
    :opened="isModalOpened"
    :loading="isLoading"
    @submit="handleSave"
    @close="toggleModal(false)"
  />

  <ServicePricesBlock
    v-for="service in filteredServices"
    :key="service.id"
    :header="service.title"
    :cars="cars as Car[]"
    :seasons="seasons as Season[]"
    :supplier-id="supplierId as number"
    :service-id="service.id"
    @click="handleEdit"
  />
</template>
