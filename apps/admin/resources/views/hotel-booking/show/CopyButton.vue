<script setup lang="ts">

import { computed } from 'vue'

import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'

const bookingStore = useBookingStore()

const canCopy = computed(() => bookingStore.availableActions?.canCopy || false)

const handleCopyBooking = () => {
  bookingStore.copy()
}

</script>

<template>
  <div>
    <a v-if="canCopy" href="#" @click.prevent="handleCopyBooking">Копировать</a>
  </div>
</template>
