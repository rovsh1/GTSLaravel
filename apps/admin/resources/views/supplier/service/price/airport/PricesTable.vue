<script setup lang="ts">

import { onMounted, ref } from 'vue'

import { useToggle } from '@vueuse/core'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import PricesModal from '~resources/views/supplier/service/price/ components/PricesModal.vue'
import { useCurrenciesStore } from '~resources/views/supplier/service/price/composables/currency'

import { Airport, Money, Season } from '~api/models'
import { ServicePriceResponse, updateAirportPrice, useServiceProviderAirportPricesAPI } from '~api/supplier/airport'

import { formatPeriod } from '~lib/date'

const props = defineProps<{
  header: string
  supplierId: number
  serviceId: number
  seasons: Season[]
  airports: Airport[]
}>()

const { data: servicePrices, execute: fetchPrices } = useServiceProviderAirportPricesAPI({
  supplierId: props.supplierId,
  serviceId: props.serviceId,
})

const { getCurrencyChar } = useCurrenciesStore()
const editableServicePrice = ref<ServicePriceResponse>()
const [isOpenedModal, toggleModal] = useToggle()
const isModalLoading = ref<boolean>(false)

const handleChangePrice = async (priceNet?: number, pricesGross?: Money[]): Promise<void> => {
  if (!pricesGross && !priceNet) {
    return
  }
  isModalLoading.value = true
  await updateAirportPrice({
    seasonId: editableServicePrice.value?.season_id as number,
    airportId: editableServicePrice.value?.airport_id as number,
    serviceId: props.serviceId,
    supplierId: props.supplierId,
    priceNet,
    pricesGross,
    currencyId: 1,
  })
  fetchPrices()
  isModalLoading.value = false
  toggleModal()
}

const getServicePrice = (seasonId: number, airportId: number): ServicePriceResponse | undefined =>
  servicePrices.value?.find((servicePrice) => servicePrice.airport_id === airportId && servicePrice.season_id === seasonId)

const getPriceButtonText = (seasonId: number, airportId: number): string => {
  const servicePrice = getServicePrice(seasonId, airportId)
  if (!servicePrice) {
    return 'Не установлена'
  }

  return `${servicePrice.price_net} ${getCurrencyChar(servicePrice.currency_id)}`
}

const handleEditServicePrice = (seasonId: number, airportId: number) => {
  const servicePrice = getServicePrice(seasonId, airportId)
  if (servicePrice) {
    editableServicePrice.value = servicePrice
  } else {
    editableServicePrice.value = {
      season_id: seasonId,
      airport_id: airportId,
      service_id: props.serviceId,
    } as unknown as ServicePriceResponse
  }
  toggleModal(true)
}

onMounted(() => {
  fetchPrices()
})

</script>

<template>
  <PricesModal
    :opened="isOpenedModal"
    :loading="isModalLoading"
    :service-price="editableServicePrice"
    @close="toggleModal(false)"
    @submit="handleChangePrice"
  />

  <CollapsableBlock :id="`airport-service-prices-${serviceId}`" :title="header" class="card-grid mb-3">
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Аэропорт</th>
          <th scope="col">Город</th>
          <th v-for="season in seasons" :key="season.id" scope="col" colspan="2">
            {{ season.number }} ({{ formatPeriod(season) }})
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="airport in airports" :key="airport.id">
          <td>
            {{ airport.name }}
          </td>
          <td>{{ airport.city_name }}</td>

          <template v-for="season in seasons" :key="season.id">
            <td>
              <a href="#" @click.prevent="handleEditServicePrice(season.id, airport.id)">
                {{ getPriceButtonText(season.id, airport.id) }}
              </a>
            </td>
          </template>
        </tr>
      </tbody>
    </table>
  </CollapsableBlock>
</template>
