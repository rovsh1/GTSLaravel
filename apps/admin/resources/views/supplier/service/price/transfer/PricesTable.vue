<script setup lang="ts">

import { onMounted, ref, watch } from 'vue'

import { formatPeriod } from 'gts-common/helpers/date'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'

import { Car, Season } from '~api/models'
import { ServicePriceResponse, useServiceProviderTransferPricesAPI } from '~api/supplier/transfer'

const props = defineProps<{
  header: string
  supplierId: number
  serviceId: number
  seasons: Season[]
  reFetchPrices: boolean
  cars: Car[]
}>()

const emit = defineEmits<{
  (event: 'editableServicePrice', servicePrice: ServicePriceResponse, serviceId: number): void
}>()

const { data: servicePrices, execute: fetchPrices } = useServiceProviderTransferPricesAPI({
  supplierId: props.supplierId,
  serviceId: props.serviceId,
})

const editableServicePrice = ref<ServicePriceResponse>()

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
  emit('editableServicePrice', editableServicePrice.value, props.serviceId)
}

watch(() => props.reFetchPrices, () => {
  if (props.reFetchPrices) {
    fetchPrices()
  }
})

onMounted(() => {
  fetchPrices()
})

</script>

<template>
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
