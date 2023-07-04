<script setup lang="ts">

import { computed } from 'vue'

import { Airport, Currency, Season } from '~api/models'
import { ServicePriceResponse, updateAirportPrice, useServiceProviderAirportPricesAPI } from '~api/service-provider/airport'

import { formatPeriod } from '~lib/date'

import EditableCell from '~components/EditableCell.vue'

const props = defineProps<{
  header: string
  providerId: number
  serviceId: number
  seasons: Season[]
  airports: Airport[]
  currencies: Currency[]
}>()

const { data: servicePrices, execute: fetchPrices } = useServiceProviderAirportPricesAPI({
  providerId: props.providerId,
  serviceId: props.serviceId,
})

const currencies = computed<Currency[]>(() => props.currencies)
console.log(currencies)

const handleChangePrice = async (seasonId: number, airportId: number, priceNet?: number, priceGross?: number): Promise<void> => {
  if (!priceGross && !priceNet) {
    return
  }
  await updateAirportPrice({
    seasonId,
    airportId,
    serviceId: props.serviceId,
    providerId: props.providerId,
    priceNet,
    priceGross,
    currencyId: 1,
  })
  fetchPrices()
}

const getServicePrice = (seasonId: number, airportId: number): ServicePriceResponse | undefined =>
  servicePrices.value?.find((servicePrice) => servicePrice.airport_id === airportId && servicePrice.season_id === seasonId)

fetchPrices()

</script>

<template>
  <div class="card card-form mb-4">
    <div class="card-header">
      <h5 class="mb-0">{{ header }}</h5>
    </div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Аэропорт</th>
            <th scope="col">Город</th>
            <th v-for="season in seasons" :key="season.id" scope="col" colspan="2">
              {{ season.number }} ({{ formatPeriod(season) }})
            </th>
          </tr>
          <tr>
            <th scope="col" colspan="2" />
            <template v-for="season in seasons" :key="season.id">
              <th scope="col">Нетто (UZS)</th>
              <th scope="col">Брутто (UZS)</th>
            </template>
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
                <EditableCell
                  :value="getServicePrice(season.id, airport.id)?.price_net"
                  placeholder="Введите цену"
                  @change="(price) => handleChangePrice(season.id, airport.id, price)"
                />
              </td>
              <td>
                <EditableCell
                  :value="getServicePrice(season.id, airport.id)?.price_gross"
                  placeholder="Введите цену"
                  @change="(price) => handleChangePrice(season.id, airport.id, undefined, price)"
                />
              </td>
            </template>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
