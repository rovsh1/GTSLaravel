<script setup lang="ts">

import BookingExternalNumberType from '~resources/views/booking/shared/components/BookingPanel/components/BookingExternalNumberType.vue'
import BookingRequests from '~resources/views/booking/shared/components/BookingPanel/components/BookingRequests.vue'
import BookingStatus from '~resources/views/booking/shared/components/BookingPanel/components/BookingStatus.vue'
import BookingStatusHistory from '~resources/views/booking/shared/components/BookingPanel/components/BookingStatusHistory.vue'
import BookingTotalAmount from '~resources/views/booking/shared/components/BookingPanel/components/BookingTotalAmount.vue'
import ControlPanelSection from '~resources/views/booking/shared/components/ControlPanelSection.vue'

</script>

<template>
  <div class="d-flex flex-wrap gap-2 align-items-center rounded shadow-lg p-4">
    <BookingStatus />
    <BookingStatusHistory />
    <BookingTotalAmount />
  </div>

  <ControlPanelSection
    title="Тип номера подтверждения бронирования"
  >
    <BookingExternalNumberType />
  </ControlPanelSection>

  <ControlPanelSection title="Запросы на бронирование услуги">
    <BookingRequests />
  </ControlPanelSection>
</template>
