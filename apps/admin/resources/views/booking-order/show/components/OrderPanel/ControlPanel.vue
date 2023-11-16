<script setup lang="ts">

import ControlPanelSection from '~resources/views/booking/shared/components/ControlPanelSection.vue'
import OrderGuests from '~resources/views/booking-order/show/components/OrderPanel/components/OrderGuests.vue'
import OrderRequests from '~resources/views/booking-order/show/components/OrderPanel/components/OrderRequests.vue'
import OrderStatus from '~resources/views/booking-order/show/components/OrderPanel/components/OrderStatus.vue'
import OrderTotalAmount from '~resources/views/booking-order/show/components/OrderPanel/components/OrderTotalAmount.vue'

</script>

<template>
  <div class="d-flex flex-wrap flex-grow-1 gap-2 align-items-center rounded shadow-lg p-4">
    <OrderStatus />
    <OrderTotalAmount />
  </div>
  <ControlPanelSection title="Файлы, отправленные клиенту">
    <OrderRequests />
  </ControlPanelSection>
  <div class="d-flex flex-wrap flex-grow-1 mt-3">
    <OrderGuests />
  </div>
</template>
