<script setup lang="ts">

import { computed } from 'vue'

import { useBookingStore } from '~resources/views/booking/shared/store/booking'

const bookingStore = useBookingStore()
const statusReason = computed(() => bookingStore.booking?.status.reason)

</script>

<template>
  <div v-if="statusReason" class="mt-2 alert-warning" role="alert">
    Причина отмены: {{ statusReason }}
  </div>
</template>

<style lang="scss" scoped>
.alert-warning {
  padding: 0.438rem 9.75rem 0.438rem 1rem;
  border-radius: 4px;
  background: #fff4de;
  color: black;
}

</style>
