<script setup lang="ts">

import { computed } from 'vue'

import { getGenderName } from '~resources/views/booking/shared/lib/constants'
import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'

import { CountryResponse } from '~api/country'
import { Guest } from '~api/order/guest'

const props = withDefaults(defineProps<{
  guestIds?: number[]
  countries: CountryResponse[]
  orderGuests: Guest[]
  canEdit: boolean
  canEditGuest?: boolean
  canDeleteGuest?: boolean
}>(), {
  guestIds: undefined,
  canEditGuest: true,
  canDeleteGuest: true,
})

defineEmits<{
  (event: 'edit', guest: Guest): void
  (event: 'delete', guest: Guest): void
}>()

const countries = computed(() => props.countries)

const getCountryName = (id: number): string | undefined =>
  countries.value?.find((country: CountryResponse) => country.id === id)?.name

const guests = computed(
  () => props.orderGuests.filter((guest) => props.guestIds && props.guestIds.includes(guest.id)),
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
        <th v-if="canEdit" />
      </tr>
    </thead>
    <tbody>
      <template v-if="guests.length > 0">
        <tr v-for="(guest, idx) in guests" :key="guest.id">
          <td>{{ idx + 1 }}</td>
          <td>{{ guest.fullName }}</td>
          <td>{{ getCountryName(guest.countryId) }}</td>
          <td>{{ getGenderName(guest.gender) }}</td>
          <td>{{ guest.isAdult ? 'Взрослый' : 'Ребенок' }}</td>
          <td v-if="canEdit" class="column-edit">
            <EditTableRowButton
              :can-edit="canEditGuest"
              :can-delete="canDeleteGuest"
              @edit="$emit('edit', guest)"
              @delete="$emit('delete', guest)"
            />
          </td>
        </tr>
      </template>
      <template v-else>
        <tr>
          <td colspan="6" class="text-center">Гости не добавлены</td>
        </tr>
      </template>
    </tbody>
  </table>
</template>
