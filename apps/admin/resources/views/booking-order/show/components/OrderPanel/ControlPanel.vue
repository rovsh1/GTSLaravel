<script setup lang="ts">

import ControlPanelSection from '~resources/views/booking/shared/components/ControlPanelSection.vue'
import OrderGuests from '~resources/views/booking-order/show/components/OrderPanel/components/OrderGuests.vue'
import OrderInvoice from '~resources/views/booking-order/show/components/OrderPanel/components/OrderInvoice.vue'
import OrderStatus from '~resources/views/booking-order/show/components/OrderPanel/components/OrderStatus.vue'
import OrderTotalAmount from '~resources/views/booking-order/show/components/OrderPanel/components/OrderTotalAmount.vue'
import OrderVoucher from '~resources/views/booking-order/show/components/OrderPanel/components/OrderVoucher.vue'

</script>
<template>
  <div class="d-flex flex-wrap flex-grow-1 gap-2 align-items-center rounded shadow-lg p-4">
    <OrderStatus />
    <OrderTotalAmount />
  </div>
  <ControlPanelSection title="Файлы, отправленные клиенту">
    <div class="mb-2">
      <OrderVoucher />
    </div>
    <div>
      <OrderInvoice />
    </div>
  </ControlPanelSection>
  <div class="d-flex flex-wrap flex-grow-1 mt-3">
    <OrderGuests />
  </div>
</template>
