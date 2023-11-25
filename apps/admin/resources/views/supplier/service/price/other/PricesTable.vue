<script setup lang="ts">

import { onMounted, ref, watch } from 'vue'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'

import { Season } from '~api/models'
import { ServicePriceResponse } from '~api/supplier/airport'
import { useServiceProviderOtherPricesAPI } from '~api/supplier/other'

import { formatPeriod } from '~lib/date'

const props = defineProps<{
  header: string
  supplierId: number
  serviceId: number
  seasons: Season[]
  reFetchPrices: boolean
}>()

const emit = defineEmits<{
  (event: 'editableServicePrice', servicePrice: ServicePriceResponse, serviceId: number): void
}>()

const { data: servicePrices, execute: fetchPrices } = useServiceProviderOtherPricesAPI({
  supplierId: props.supplierId,
  serviceId: props.serviceId,
})

const editableServicePrice = ref<ServicePriceResponse>()

const getServicePrice = (seasonId: number): ServicePriceResponse | undefined =>
  servicePrices.value?.find((servicePrice) => servicePrice.season_id === seasonId)

const getPriceButtonText = (seasonId: number): string => {
  const servicePrice = getServicePrice(seasonId)
  if (!servicePrice) {
    return 'Не установлена'
  }

  return `${servicePrice.price_net} ${servicePrice.currency}`
}

const handleEditServicePrice = (seasonId: number) => {
  const servicePrice = getServicePrice(seasonId)
  if (servicePrice) {
    editableServicePrice.value = servicePrice
  } else {
    editableServicePrice.value = {
      season_id: seasonId,
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
          <th v-for="season in seasons" :key="season.id" scope="col">
            {{ season.number }} ({{ formatPeriod(season) }})
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <template v-for="season in seasons" :key="season.id">
            <td>
              <a href="#" @click.prevent="handleEditServicePrice(season.id)">
                {{ getPriceButtonText(season.id) }}
              </a>
            </td>
          </template>
        </tr>
      </tbody>
    </table>
  </CollapsableBlock>
</template>
