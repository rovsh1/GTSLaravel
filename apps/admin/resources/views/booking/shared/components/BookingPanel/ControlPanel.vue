<script setup lang="ts">

import BookingCancelConditions from '~resources/views/booking/shared/components/BookingPanel/components/BookingCancelConditions.vue'
import BookingExternalNumberType from '~resources/views/booking/shared/components/BookingPanel/components/BookingExternalNumberType.vue'
import BookingFinancialCost from '~resources/views/booking/shared/components/BookingPanel/components/BookingFinancialCost.vue'
import BookingRequests from '~resources/views/booking/shared/components/BookingPanel/components/BookingRequests.vue'
import BookingStatus from '~resources/views/booking/shared/components/BookingPanel/components/BookingStatus.vue'
import BookingStatusHistory from '~resources/views/booking/shared/components/BookingPanel/components/BookingStatusHistory.vue'
import BookingTotalAmount from '~resources/views/booking/shared/components/BookingPanel/components/BookingTotalAmount.vue'
import ControlPanelSection from '~resources/views/booking/shared/components/ControlPanelSection.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

const bookingStore = useBookingStore()
const { recalculatePrice } = bookingStore

</script>

<template>
  <div class="d-flex flex-wrap flex-grow-1 gap-2 align-items-center rounded shadow-lg p-4">
    <BookingStatus />
    <BookingStatusHistory />
    <BookingTotalAmount />
  </div>

  <ControlPanelSection
    v-if="bookingStore.isHotelBooking"
    title="Тип номера подтверждения бронирования"
  >
    <BookingExternalNumberType />
  </ControlPanelSection>

  <ControlPanelSection title="Финансовая стоимость брони">
    <OverlayLoading v-if="bookingStore.isRecalculateBookingPrice" />
    <template #actions>
      <BootstrapButton
        label="Пересчитать"
        size="small"
        severity="secondary"
        variant="outline"
        :disabled="bookingStore.isRecalculateBookingPrice"
        @click="recalculatePrice"
      />
    </template>
    <BookingFinancialCost />
  </ControlPanelSection>

  <ControlPanelSection title="Запросы на бронирование услуги">
    <BookingRequests />
  </ControlPanelSection>

  <ControlPanelSection title="Условия отмены">
    <BookingCancelConditions />
  </ControlPanelSection>
</template>
