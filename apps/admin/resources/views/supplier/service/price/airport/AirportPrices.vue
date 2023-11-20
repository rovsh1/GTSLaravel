<script setup lang="ts">

import { computed } from 'vue'

import { z } from 'zod'

import { useQuickSearch } from '~resources/composables/quick-search'
import { useCurrenciesStore } from '~resources/views/supplier/service/price/composables/currency'

import { Airport, Season } from '~api/models'

import { requestInitialData } from '~lib/initial-data'

import PricesTable from './PricesTable.vue'

const { seasons, services, airports, supplierId, currencies } = requestInitialData('view-initial-data-supplier', z.object({
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
  airports: z.array(z.object({
    id: z.number(),
    name: z.string(),
    city_id: z.number(),
    code: z.string(),
    city_name: z.string(),
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

const { quickSearch, isEmpty } = useQuickSearch()

const filteredServices = computed(() => {
  if (quickSearch && !isEmpty) {
    return services.filter((service) => service.title.toLowerCase().includes(quickSearch.toLowerCase()))
  }
  return services
})

</script>

<template>
  <PricesTable
    v-for="service in filteredServices"
    :key="service.id"
    :header="service.title"
    :airports="airports as Airport[]"
    :seasons="seasons as Season[]"
    :supplier-id="supplierId as number"
    :service-id="service.id"
  />
</template>
