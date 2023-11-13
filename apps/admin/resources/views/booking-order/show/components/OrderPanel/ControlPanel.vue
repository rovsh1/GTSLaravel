<script setup lang="ts">

import OrderStatus from '~resources/views/booking-order/show/components/OrderPanel/components/OrderStatus.vue'
import OrderTotalAmount from '~resources/views/booking-order/show/components/OrderPanel/components/OrderTotalAmount.vue'

</script>

<template>
  <div class="d-flex flex-wrap flex-grow-1 gap-2 align-items-center rounded shadow-lg p-4">
    <OrderStatus />
    <OrderTotalAmount />
  </div>
</template>
