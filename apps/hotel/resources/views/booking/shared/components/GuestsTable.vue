<script setup lang="ts">

import { computed } from 'vue'

import { getGenderName } from '~resources/views/booking/shared/lib/constants'

import { CountryResponse } from '~api/country'
import { Guest } from '~api/order/guest'

const props = withDefaults(defineProps<{
  guestIds?: number[]
  countries: CountryResponse[]
  orderGuests: Guest[]
}>(), {
  guestIds: undefined,
})

const countries = computed(() => props.countries)

const getCountryName = (id: number): string | undefined =>
  countries.value?.find((country: CountryResponse) => country.id === id)?.name

const guests = computed(
  () => props.orderGuests.filter((guest) => props.guestIds && props.guestIds.includes(guest.id)),
)

</script>

<template>
  <table class="table table-striped mb-0">
    <thead>
      <tr>
        <th>№</th>
        <th class="column-text">ФИО</th>
        <th class="column-text">Пол</th>
        <th class="column-text">Гражданство</th>
        <th class="column-text">Тип</th>
      </tr>
    </thead>
    <tbody>
      <template v-if="guests.length > 0">
        <tr v-for="(guest, idx) in guests" :key="guest.id">
          <td>{{ idx + 1 }}</td>
          <td>{{ guest.fullName }}</td>
          <td>{{ getGenderName(guest.gender) }}</td>
          <td>{{ getCountryName(guest.countryId) }}</td>
          <td>{{ guest.isAdult ? 'Взрослый' : 'Ребенок' }}</td>
        </tr>
      </template>
      <template v-else>
        <tr>
          <td colspan="5" class="text-center">Гости не добавлены</td>
        </tr>
      </template>
    </tbody>
  </table>
</template>
