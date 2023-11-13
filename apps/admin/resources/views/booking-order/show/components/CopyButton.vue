<script setup lang="ts">

import { computed } from 'vue'

import { useOrderStore } from '~resources/views/booking-order/show/store/order'

const orderStore = useOrderStore()

const canCopy = computed(() => orderStore.availableActions?.canCopy || false)

const handleCopyBooking = () => {
  orderStore.copy()
}

</script>

<template>
  <div style="margin-left: 1rem;">
    <a v-if="canCopy" href="#" @click.prevent="handleCopyBooking">Копировать</a>
  </div>
</template>
