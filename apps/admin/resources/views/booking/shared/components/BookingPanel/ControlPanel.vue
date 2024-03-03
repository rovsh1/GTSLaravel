<script setup lang="ts">

import OverlayLoading from 'gts-components/Base/OverlayLoading'
import BootstrapButton from 'gts-components/Bootstrap/BootstrapButton/BootstrapButton'

import BookingExternalNumberType from '~resources/views/booking/shared/components/BookingPanel/components/BookingExternalNumberType.vue'
import BookingFinancialCost from '~resources/views/booking/shared/components/BookingPanel/components/BookingFinancialCost.vue'
import BookingRequests from '~resources/views/booking/shared/components/BookingPanel/components/BookingRequests.vue'
import BookingStatus from '~resources/views/booking/shared/components/BookingPanel/components/BookingStatus.vue'
import BookingStatusHistory from '~resources/views/booking/shared/components/BookingPanel/components/BookingStatusHistory.vue'
import BookingTotalAmount from '~resources/views/booking/shared/components/BookingPanel/components/BookingTotalAmount.vue'
import BookingTravelineFlag from '~resources/views/booking/shared/components/BookingPanel/components/BookingTravelineFlag.vue'
import ControlPanelSection from '~resources/views/booking/shared/components/ControlPanelSection.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

const bookingStore = useBookingStore()
const { recalculatePrice } = bookingStore

</script>

<template>
  <div class="d-flex flex-wrap gap-2 align-items-center rounded shadow-lg p-4">
    <BookingTravelineFlag />
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

  <ControlPanelSection title="Финансовая стоимость брони" class="d-flex flex-column flex-grow-1">
    <OverlayLoading v-if="bookingStore.isRecalculateBookingPrice" />
    <template #actions>
      <BootstrapButton
        v-if="bookingStore.availableActions?.isEditable || false"
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

  <ControlPanelSection v-if="!bookingStore.isOtherServiceBooking" title="Запросы на бронирование услуги">
    <BookingRequests />
  </ControlPanelSection>
</template>
