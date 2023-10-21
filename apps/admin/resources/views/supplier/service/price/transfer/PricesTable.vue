<script setup lang="ts">

import { onMounted, ref } from 'vue'

import { useToggle } from '@vueuse/core'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import PricesModal from '~resources/views/supplier/service/price/ components/PricesModal.vue'

import { Car, Money, Season } from '~api/models'
import { ServicePriceResponse, updateCarPrice, useServiceProviderTransferPricesAPI } from '~api/supplier/transfer'

import { formatPeriod } from '~lib/date'

const props = defineProps<{
  header: string
  supplierId: number
  serviceId: number
  seasons: Season[]
  cars: Car[]
}>()

const { data: servicePrices, execute: fetchPrices } = useServiceProviderTransferPricesAPI({
  supplierId: props.supplierId,
  serviceId: props.serviceId,
})

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
    supplierId: props.supplierId,
    priceNet,
    pricesGross,
    currency: 'UZS', // @todo валюта поставщика,
  })
  fetchPrices()
  isModalLoading.value = false
  toggleModal()
}

const getServicePrice = (seasonId: number, carId: number): ServicePriceResponse | undefined =>
  servicePrices.value?.find((servicePrice) => servicePrice.car_id === carId && servicePrice.season_id === seasonId)

const getPriceButtonText = (seasonId: number, carId: number): string => {
  const servicePrice = getServicePrice(seasonId, carId)
  if (!servicePrice) {
    return 'Не установлена'
  }

  return `${servicePrice.price_net} ${servicePrice.currency}`
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
