<script setup lang="ts">

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'

import { Car, Season } from '~api/models'

import { formatPeriod } from '~lib/date'

const props = defineProps<{
  header: string
  serviceId: number
  seasons: Season[]
  cars: Car[]
}>()

const emit = defineEmits<{
  (event: 'click', serviceId: number, seasonId: number, carId: number): void
}>()

const getPriceButtonText = (seasonId: number, carId: number): string => 'Не установлено'

const handleClick = (seasonId: number, carId: number): void => {
  emit('click', props.serviceId, seasonId, carId)
}

</script>

<template>
  <CollapsableBlock :id="`transfer-service-cancel-conditions-${serviceId}`" :title="header" class="card-grid mb-3">
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
              <a href="#" @click.prevent="handleClick(season.id, car.id)">
                {{ getPriceButtonText(season.id, car.id) }}
              </a>
            </td>
          </template>
        </tr>
      </tbody>
    </table>
  </CollapsableBlock>
</template>
