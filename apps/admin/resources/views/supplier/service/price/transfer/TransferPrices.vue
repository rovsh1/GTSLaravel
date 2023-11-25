<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import { useQuickSearch } from '~resources/composables/quick-search'
import PricesModal from '~resources/views/supplier/service/price/ components/PricesModal.vue'
import { useCurrenciesStore } from '~resources/views/supplier/service/price/composables/currency'

import { Car, Money, Season } from '~api/models'
import { ServicePriceResponse, updateCarPrice } from '~api/supplier/transfer'

import { requestInitialData } from '~lib/initial-data'

import PricesTable from './PricesTable.vue'

const { seasons, services, cars, supplierId, currencies } = requestInitialData('view-initial-data-supplier', z.object({
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
  currencies: z.array(z.object({
    id: z.number(),
    code_num: z.number(),
    code_char: z.string(),
    sign: z.string(),
    name: z.string(),
  })),
}))

useCurrenciesStore().setCurrencies(currencies)

const editableServicePrice = ref<ServicePriceResponse>()

const { quickSearch, isEmpty } = useQuickSearch()

const editableServiceId = ref<number>()

const [isOpenedModal, toggleModal] = useToggle()
const isModalLoading = ref<boolean>(false)

const isReFetchPrices = ref<boolean>(false)

const filteredServices = computed(() => {
  if (quickSearch && !isEmpty) {
    return services.filter((service) => service.title.toLowerCase().includes(quickSearch.toLowerCase()))
  }
  return services
})

const handleChangePrice = async (priceNet?: number, pricesGross?: Money[]): Promise<void> => {
  if ((!pricesGross && !priceNet) || editableServiceId.value === undefined) {
    return
  }
  isModalLoading.value = true
  await updateCarPrice({
    seasonId: editableServicePrice.value?.season_id as number,
    carId: editableServicePrice.value?.car_id as number,
    serviceId: editableServiceId.value,
    supplierId,
    priceNet,
    pricesGross,
    currency: 'UZS',
  })
  isReFetchPrices.value = true
  isModalLoading.value = false
  toggleModal()
}

</script>

<template>
  <PricesModal
    :opened="isOpenedModal"
    :loading="isModalLoading"
    :service-price="editableServicePrice"
    @close="toggleModal(false)"
    @submit="(netPrice, grossPrices) => {
      handleChangePrice(netPrice, grossPrices)
      isReFetchPrices = false
    }"
  />
  <PricesTable
    v-for="service in filteredServices"
    :key="service.id"
    :header="service.title"
    :cars="cars as Car[]"
    :seasons="seasons as Season[]"
    :supplier-id="supplierId as number"
    :service-id="service.id"
    :re-fetch-prices="isReFetchPrices"
    @editable-service-price="(servicePrice, serviceId) => {
      editableServicePrice = servicePrice
      editableServiceId = serviceId
      toggleModal(true)
    }"
  />
</template>
