<script setup lang="ts">
import { computed } from 'vue'

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'

import { Car } from '~api/booking/order/cars'

const props = defineProps<{
  carIds?: number[]
  orderCars: Car[]
  canEdit: boolean
}>()

defineEmits<{
  (event: 'edit', car: Car): void
  (event: 'delete', car: Car): void
}>()

const cars = computed(
  () => props.orderCars.filter((car) => props.carIds && props.carIds.includes(car.id)),
)

</script>

<template>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>№</th>
        <th class="column-text">Модель</th>
        <th class="column-text">Кол-во авто</th>
        <th class="column-text">Кол-во пассажиров</th>
        <th class="column-text">Кол-во багажа</th>
        <th v-if="canEdit" />
      </tr>
    </thead>
    <tbody>
      <template v-if="cars.length > 0">
        <tr v-for="(car, idx) in cars" :key="car.id">
          <td>{{ idx + 1 }}</td>
          <td>{{ car.carModel }}</td>
          <td>{{ car.carCount }}</td>
          <td>{{ car.passengerCount }}</td>
          <td>{{ car.baggageCount }}</td>
          <td v-if="canEdit" class="column-edit">
            <EditTableRowButton
              @edit="$emit('edit', car)"
              @delete="$emit('delete', car)"
            />
          </td>
        </tr>
      </template>
      <template v-else>
        <tr>
          <td colspan="6" class="text-center">Автомобили не добавлены</td>
        </tr>
      </template>
    </tbody>
  </table>
</template>
