<script setup lang="ts">

import { computed } from 'vue'

import { useBookingStore } from '~resources/views/booking/services/show/store/booking'

const bookingStore = useBookingStore()

const canCopy = computed(() => bookingStore.availableActions?.canCopy || false)

const handleCopyBooking = () => {
  bookingStore.copy()
}

</script>

<template>
  <div style="margin-left: 1rem;">
    <a v-if="canCopy" href="#" @click.prevent="handleCopyBooking">Копировать</a>
  </div>
</template>
