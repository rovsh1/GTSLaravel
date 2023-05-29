<script setup lang="ts">

import { computed, reactive, ref, watch } from 'vue'

import { z } from 'zod'

import { ExternalNumberTypeEnum, externalNumberTypeOptions } from '~resources/views/hotel-booking/show/constants'

import { Booking, updateBookingStatus, useGetBookingAPI } from '~api/booking'
import { useBookingAvailableStatusesAPI } from '~api/booking/status'

import { requestInitialData } from '~lib/initial-data'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'

import StatusSelect from './components/StatusSelect.vue'

const { bookingID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
  }),
)

const externalNumberType = ref<number | string>()
const externalNumber = ref<string>()
const isNeedShowExternalNumber = computed<boolean>(
  () => Number(externalNumberType.value) === ExternalNumberTypeEnum.HotelBookingNumber
    || Number(externalNumberType.value) === ExternalNumberTypeEnum.FullName,
)

const isExternalNumberChanged = ref<boolean>(false)
watch(externalNumberType, () => {
  isExternalNumberChanged.value = true
})
watch(externalNumber, () => {
  isExternalNumberChanged.value = true
})

const { data: bookingData, execute: fetchBooking } = useGetBookingAPI({ bookingID })
const { data: availableStatuses, execute: fetchAvailableStatuses } = useBookingAvailableStatusesAPI({ bookingID })

fetchBooking()
fetchAvailableStatuses()

const booking = reactive<Booking>(bookingData as unknown as Booking)

const handleStatusChange = async (value: number): Promise<void> => {
  await updateBookingStatus({
    bookingID,
    status: value,
  })
  fetchBooking()
}

</script>

<template>
  <div class="d-flex flex-wrap flex-grow-1 gap-2">
    <StatusSelect
      v-if="booking"
      v-model="booking.status"
      :available-statuses="availableStatuses"
      @change="handleStatusChange"
    />
    <a href="#" class="btn-log">История изменений</a>
    <div class="float-end">
      Общая сумма: <strong>0 <span class="cur">сўм</span></strong>
    </div>
  </div>

  <div class="mt-4">
    <h6>Тип номера подтверждения бронирования</h6>
    <hr>
    <div class="d-flex align-content-around">
      <div>
        <BootstrapSelectBase
          id="external_number_type"
          disabled-placeholder="Номер подтверждения брони в отеля"
          :value="externalNumberType as number"
          :options="externalNumberTypeOptions"
          @input="value => externalNumberType = value as number"
        />
      </div>
      <div class="d-flex ml-2">
        <div class="external-number-wrapper">
          <input
            v-if="isNeedShowExternalNumber"
            v-model="externalNumber"
            class="form-control"
            name="external_number"
            type="text"
            placeholder="№ брони"
          >
          <div class="invalid-feedback">
            Пожалуйста, заполните номер брони.
          </div>
        </div>
        <div v-if="isExternalNumberChanged" class="ml-2">
          <a class="btn btn-primary">Сохранить</a>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <h6>Финансовая стоимость брони</h6>
    <hr>
  </div>
</template>

<style scoped lang="scss">
.ml-2 {
  margin-left: 0.5rem;
}
</style>
