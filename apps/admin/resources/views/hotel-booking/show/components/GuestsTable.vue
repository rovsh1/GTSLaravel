<script setup lang="ts">

import { computed } from 'vue'

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import { getGenderName } from '~resources/views/hotel-booking/show/lib/constants'

import { HotelBookingGuest } from '~api/booking/hotel/details'
import { CountryResponse } from '~api/country'

const props = defineProps<{
  guests: HotelBookingGuest[]
  countries: CountryResponse[]
  canEdit: boolean
}>()

defineEmits<{
  (event: 'edit', guest: HotelBookingGuest): void
  (event: 'delete', guest: HotelBookingGuest): void
}>()

const countries = computed(() => props.countries)

const getCountryName = (id: number): string | undefined =>
  countries.value?.find((country: CountryResponse) => country.id === id)?.name

</script>

<template>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>№</th>
        <th class="column-text">ФИО</th>
        <th class="column-text">Пол</th>
        <th class="column-text">Гражданство</th>
        <th class="column-text">Тип</th>
        <th class="column-text" />
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="(guest, idx) in guests" :key="guest.id">
        <td>{{ idx + 1 }}</td>
        <td>{{ guest.fullName }}</td>
        <td>{{ getCountryName(guest.countryId) }}</td>
        <td>{{ getGenderName(guest.gender) }}</td>
        <td>{{ guest.isAdult ? 'Взрослый' : 'Ребенок' }}</td>
        <td class="column-edit">
          <EditTableRowButton
            v-if="canEdit"
            @edit="$emit('edit', guest)"
            @delete="$emit('delete', guest)"
          />
        </td>
      </tr>
    </tbody>
  </table>
</template>
