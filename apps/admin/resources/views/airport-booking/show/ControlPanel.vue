<script setup lang="ts">

import { computed } from 'vue'

import { useToggle } from '@vueuse/core'

import { useBookingStore } from '~resources/views/airport-booking/show/store/booking'
import ControlPanelSection from '~resources/views/hotel-booking/show/components/ControlPanelSection.vue'
import StatusSelect from '~resources/views/hotel-booking/show/components/StatusSelect.vue'

import { BookingAvailableActionsResponse } from '~api/booking/hotel/status'
import { BookingStatusResponse } from '~api/booking/models'

const [isHistoryModalOpened, toggleHistoryModal] = useToggle<boolean>(false)
console.log(isHistoryModalOpened)

const bookingStore = useBookingStore()
const booking = computed(() => bookingStore.booking)
const statuses = computed<BookingStatusResponse[] | null>(() => bookingStore.statuses)
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)

const handleStatusChange = () => {
  console.log('change')
}

</script>

<template>
  <!--  <StatusHistoryModal-->
  <!--    :opened="isHistoryModalOpened"-->
  <!--    @close="toggleHistoryModal(false)"-->
  <!--  />-->

  <div class="d-flex flex-wrap flex-grow-1 gap-2">
    <StatusSelect
      v-if="booking && statuses"
      v-model="booking.status"
      :statuses="statuses"
      :available-statuses="availableActions?.statuses || null"
      @change="handleStatusChange"
    />
    <a href="#" class="btn-log" @click.prevent="toggleHistoryModal()">История изменений</a>
  </div>

  <ControlPanelSection
    title="Запросы на бронирование услуги"
    class="mt-4"
  />

  <ControlPanelSection title="Условия отмены" class="mt-4" />
</template>
