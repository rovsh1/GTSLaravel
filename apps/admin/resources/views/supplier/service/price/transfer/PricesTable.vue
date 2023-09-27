<script setup lang="ts">

import { onMounted, ref } from 'vue'

import { useToggle } from '@vueuse/core'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import PricesModal from '~resources/views/supplier/service/price/ components/PricesModal.vue'
import { useCurrenciesStore } from '~resources/views/supplier/service/price/composables/currency'

import { CityResponse } from '~api/city'
import { Car, Money, Season } from '~api/models'
import { ServicePriceResponse, updateCarPrice, useServiceProviderTransferPricesAPI } from '~api/supplier/transfer'

import { formatPeriod } from '~lib/date'

const props = defineProps<{
  header: string
  providerId: number
  serviceId: number
  seasons: Season[]
  cars: Car[]
}>()

const { data: servicePrices, execute: fetchPrices } = useServiceProviderTransferPricesAPI({
  providerId: props.providerId,
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
  await updateCarPrice({
    seasonId: editableServicePrice.value?.season_id as number,
    carId: editableServicePrice.value?.car_id as number,
    serviceId: props.serviceId,
    providerId: props.providerId,
    priceNet,
    pricesGross,
    currencyId: 1, // @todo валюта поставщика,
  })
  fetchPrices()
  isModalLoading.value = false
  toggleModal()
}

const getDisplayCarCities = (cities?: CityResponse[]): string => {
  if (!cities || cities.length === 0) {
    return 'Все города'
  }
  return cities.map((city) => city.name).join(', ')
}

const getServicePrice = (seasonId: number, carId: number): ServicePriceResponse | undefined =>
  servicePrices.value?.find((servicePrice) => servicePrice.car_id === carId && servicePrice.season_id === seasonId)

const getPriceButtonText = (seasonId: number, carId: number): string => {
  const servicePrice = getServicePrice(seasonId, carId)
  if (!servicePrice) {
    return 'Не установлена'
  }

  return `${servicePrice.price_net} ${getCurrencyChar(servicePrice.currency_id)}`
}

const handleEditServicePrice = (seasonId: number, carId: number) => {
  const servicePrice = getServicePrice(seasonId, carId)
  if (servicePrice) {
    editableServicePrice.value = servicePrice
  } else {
    editableServicePrice.value = {
      season_id: seasonId,
      car_id: carId,
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
          <th scope="col">Автомобиль</th>
          <th scope="col">Город</th>
          <th v-for="season in seasons" :key="season.id" scope="col">
            {{ season.number }} ({{ formatPeriod(season) }})
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="car in cars" :key="car.id">
          <td>
            {{ car.mark }} {{ car.model }}
          </td>
          <td>{{ getDisplayCarCities(car.cities) }}</td>

          <template v-for="season in seasons" :key="season.id">
            <td>
              <a href="#" @click.prevent="handleEditServicePrice(season.id, car.id)">
                {{ getPriceButtonText(season.id, car.id) }}
              </a>
            </td>
          </template>
        </tr>
      </tbody>
    </table>
  </CollapsableBlock>
</template>
