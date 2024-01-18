<script setup lang="ts">

import { computed } from 'vue'

import { useBookingStore } from '~resources/views/booking/shared/store/booking'

const bookingStore = useBookingStore()
const isTravelineIntegrationEnabled = computed(() => bookingStore.booking?.details?.isTravelineIntegrationEnabled)

</script>

<template>
  <div v-if="bookingStore.isHotelBooking && isTravelineIntegrationEnabled">
    <span title="Интеграция с Traveline" class="traveline-badge large">TL</span>
  </div>
</template>

<style lang="scss" scoped>
.traveline-badge.large {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 32px;
  height: 32px;
}
</style>
