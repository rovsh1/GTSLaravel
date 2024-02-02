<script setup lang="ts">
import { computed } from 'vue'

import { z } from 'zod'

import { useExternalNumber } from '~resources/views/booking/shared/composables/external-number'
import { externalNumberTypeOptions } from '~resources/views/booking/shared/lib/constants'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { ExternalNumberType, ExternalNumberTypeEnum } from '~api/booking/hotel/details'
import { BookingAvailableActionsResponse } from '~api/booking/status'

import SelectComponent from '~components/SelectComponent.vue'

import { requestInitialData } from '~lib/initial-data'

const { bookingID } = requestInitialData(
  z.object({
    bookingID: z.number(),
  }),
)

const {
  externalNumberType,
  externalNumber,
  isExternalNumberValid,
  isUpdateExternalNumberFetching,
  isExternalNumberChanged,
  updateExternalNumber,
} = useExternalNumber(bookingID)
const bookingStore = useBookingStore()
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)
const canEditExternalNumber = computed<boolean>(() => availableActions.value?.canEditExternalNumber || false)
const isNeedShowExternalNumber = computed<boolean>(
  () => Number(externalNumberType.value) === ExternalNumberTypeEnum.HotelBookingNumber,
)
const isExternalNumberInvalid = computed(() => !isExternalNumberValid.value)

const handleUpdateExternalNumber = async () => {
  const isSuccess = await updateExternalNumber()
  if (isSuccess) {
    isExternalNumberChanged.value = false
  }
}
</script>

<template>
  <div class="d-flex flex-row gap-2" :class="{ loading: isUpdateExternalNumberFetching }">
    <div class="w-50">
      <SelectComponent
        :options="externalNumberTypeOptions"
        :disabled="!canEditExternalNumber"
        disabled-placeholder="Номер подтверждения брони в отеля"
        :value="externalNumberType"
        required
        @change="(value) => {
          externalNumberType = value as ExternalNumberType
        }"
      />
    </div>
    <div class="d-flex flex-row gap-2">
      <div v-if="isNeedShowExternalNumber" class="external-number-wrapper">
        <input
          v-model="externalNumber"
          class="form-control"
          :class="{ 'invalid-input': isExternalNumberInvalid }"
          :disabled="!canEditExternalNumber"
          type="text"
          placeholder="№ брони"
        >
        <div class="invalid-feedback" :class="{ 'd-block': isExternalNumberInvalid }">
          Пожалуйста, заполните номер брони.
        </div>
      </div>
      <div v-if="isExternalNumberChanged">
        <a href="#" class="btn btn-primary" @click.prevent="handleUpdateExternalNumber">
          Сохранить
        </a>
      </div>
    </div>
  </div>
</template>
