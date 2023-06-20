<script setup lang="ts">

import EditableCell from '~resources/views/service-provider/service/price/components/EditableCell.vue'

import { ServicePriceResponse, updateCarPrice, useServiceProviderPricesAPI } from '~api/service-provider/transfer'

import { formatPeriod } from '~lib/date'

import { Car, Season } from './lib'

const props = defineProps<{
  header: string
  providerId: number
  serviceId: number
  seasons: Season[]
  cars: Car[]
}>()

// @todo разделить енумы справочников транспорта и аэро
// @todo справочник - машины поставщика (+город мультиселект)
// @todo для аэро тоже самое (выбор город + аэропорт)

const { data: servicePrices, execute: fetchPrices } = useServiceProviderPricesAPI({
  providerId: props.providerId,
  serviceId: props.serviceId,
})

const handleChangePrice = async (seasonId: number, carId: number, priceNet?: number, priceGross?: number): Promise<void> => {
  if (!priceGross && !priceNet) {
    return
  }
  await updateCarPrice({
    seasonId,
    carId,
    serviceId: props.serviceId,
    providerId: props.providerId,
    priceNet,
    priceGross,
  })
  fetchPrices()
}

const getServicePrice = (seasonId: number, carId: number): ServicePriceResponse | undefined =>
  servicePrices.value?.find((servicePrice) => servicePrice.car_id === carId && servicePrice.season_id === seasonId)

fetchPrices()

</script>

<template>
  <div class="card card-form mb-4">
    <div class="card-header">
      {{ header }}
    </div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Автомобиль</th>
            <th scope="col">Город</th>
            <th v-for="season in seasons" :key="season.id" scope="col" colspan="2">
              {{ formatPeriod(season) }}
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
          <tr v-for="car in cars" :key="car.id">
            <td>
              {{ car.mark }} {{ car.model }}
            </td>
            <td>Город</td>

            <template v-for="season in seasons" :key="season.id">
              <td>
                <EditableCell
                  :value="getServicePrice(season.id, car.id)?.price_net"
                  placeholder="Введите цену"
                  @change="(price) => handleChangePrice(season.id, car.id, price)"
                />
              </td>
              <td>
                <EditableCell
                  :value="getServicePrice(season.id, car.id)?.price_gross"
                  placeholder="Введите цену"
                  @change="(price) => handleChangePrice(season.id, car.id, undefined, price)"
                />
              </td>
            </template>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
