<script setup lang="ts">

import { computed } from 'vue'

import { z } from 'zod'

import { useQuickSearch } from '~resources/composables/quick-search'
import { useCurrenciesStore } from '~resources/views/supplier/service/price/composables/currency'

import { Car, Season } from '~api/models'

import { requestInitialData } from '~lib/initial-data'

import PricesTable from './PricesTable.vue'

const { seasons, services, cars, providerId, currencies } = requestInitialData('view-initial-data-supplier', z.object({
  providerId: z.number(),
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
    name: z.string(),
    type: z.number(),
  })),
  cars: z.array(z.object({
    id: z.number(),
    mark: z.string(),
    model: z.string(),
    cities: z.array(z.object({
      id: z.number(),
      name: z.string(),
    })),
  })),
  currencies: z.array(z.object({
    id: z.number(),
    code_num: z.number(),
    code_char: z.string(),
    sign: z.string(),
    name: z.string(),
  })),
}))

useCurrenciesStore().setCurrencies(currencies)

const { quickSearch } = useQuickSearch()

const filteredServices = computed(() => {
  if (quickSearch && quickSearch.trim().length > 0) {
    return services.filter((service) => service.name.toLowerCase().includes(quickSearch.toLowerCase()))
  }
  return services
})

</script>

<template>
  <PricesTable
    v-for="service in filteredServices"
    :key="service.id"
    :header="service.name"
    :cars="cars as Car[]"
    :seasons="seasons as Season[]"
    :provider-id="providerId as number"
    :service-id="service.id"
  />
</template>
