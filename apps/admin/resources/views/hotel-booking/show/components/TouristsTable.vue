<script setup lang="ts">

import { computed } from 'vue'

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import { getGenderName } from '~resources/views/hotel-booking/show/lib/constants'

import { HotelBookingGuest } from '~api/booking/hotel/details'
import { Tourist } from '~api/booking/order/tourists'
import { CountryResponse } from '~api/country'

const props = defineProps<{
  tourists: HotelBookingGuest[]
  countries: CountryResponse[]
  orderTourists: Tourist[]
  canEdit: boolean
}>()

defineEmits<{
  (event: 'edit', tourist: Tourist): void
  (event: 'delete', tourist: Tourist): void
}>()

const countries = computed(() => props.countries)

const getCountryName = (id: number): string | undefined =>
  countries.value?.find((country: CountryResponse) => country.id === id)?.name

const notExistsOrderTourists = computed(
  () => props.orderTourists.filter((tourist) => !props.tourists.map((t) => t.id).includes(tourist.id)),
)

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
      <tr v-for="(tourist, idx) in tourists" :key="tourist.id">
        <td>{{ idx + 1 }}</td>
        <td>{{ tourist.fullName }}</td>
        <td>{{ getCountryName(tourist.countryId) }}</td>
        <td>{{ getGenderName(tourist.gender) }}</td>
        <td>{{ tourist.isAdult ? 'Взрослый' : 'Ребенок' }}</td>
        <td class="column-edit">
          <EditTableRowButton
            v-if="canEdit"
            @edit="$emit('edit', tourist)"
            @delete="$emit('delete', tourist)"
          />
        </td>
      </tr>

      <tr
        v-for="tourist in notExistsOrderTourists"
        :key="tourist.id"
        class="table-danger"
      >
        <td>-</td>
        <td>{{ tourist.fullName }}</td>
        <td>{{ getCountryName(tourist.countryId) }}</td>
        <td>{{ getGenderName(tourist.gender) }}</td>
        <td>{{ tourist.isAdult ? 'Взрослый' : 'Ребенок' }}</td>
        <td class="column-edit">
          -
        </td>
      </tr>
    </tbody>
  </table>
</template>
