<script setup lang="ts">

import { computed } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import { useQuickSearch } from '~resources/composables/quick-search'

import { Season } from '~api/models'

import { requestInitialData } from '~lib/initial-data'

import CancelConditionsModal from './CancelConditionsModal.vue'
import ServicePricesBlock from './ServicePricesBlock.vue'

import { useCancelConditions } from '../composables/cancel-conditions'

const { seasons, services, supplierId } = requestInitialData('view-initial-data-supplier', z.object({
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
}))

const { quickSearch, isEmpty } = useQuickSearch()

const filteredServices = computed(() => {
  if (quickSearch && !isEmpty) {
    return services.filter((service) => service.title.toLowerCase().includes(quickSearch.toLowerCase()))
  }
  return services
})

const [isModalOpened, toggleModal] = useToggle()

const { cancelConditions, load, save, isLoading, existsCancelConditions } = useCancelConditions(supplierId)

const handleEdit = (serviceId: number, seasonId: number): void => {
  toggleModal(true)
  load(serviceId, seasonId)
}

const handleSave = async (): Promise<void> => {
  await save()
  toggleModal(false)
}

const getFilteredCancelConditions = (serviceId: number) => existsCancelConditions.value?.filter(
  (condition) => condition.service_id === serviceId,
)

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
    :seasons="seasons as Season[]"
    :service-id="service.id"
    :cancel-conditions="getFilteredCancelConditions(service.id)"
    @click="handleEdit"
  />
</template>
