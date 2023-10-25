<script setup lang="ts">

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'

import { CarBid } from '~api/booking/service'

defineProps<{
  bookingCars: CarBid[]
  canEdit: boolean
}>()

defineEmits<{
  (event: 'edit', car: CarBid): void
  (event: 'delete', car: CarBid): void
}>()

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
      <template v-if="bookingCars.length > 0">
        <tr v-for="(car, idx) in bookingCars" :key="car.id">
          <td>{{ idx + 1 }}</td>
          <td>{{ `${car.carInfo.mark} ${car.carInfo.model}` }}</td>
          <td>{{ car.carsCount }}</td>
          <td>{{ car.passengersCount }}</td>
          <td>{{ car.baggageCount || 'Нет' }}</td>
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
