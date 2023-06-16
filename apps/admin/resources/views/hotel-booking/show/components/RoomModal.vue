<script setup lang="ts">

import { computed, Ref, ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import {
  getConditionLabel,
  residentTypeOptions,
  roomStatusOptions,
} from '~resources/views/hotel-booking/show/constants'
import { RoomFormData, validateForm } from '~resources/views/hotel-booking/show/form'

import { addRoomToBooking, updateBookingRoom } from '~api/booking/rooms'
import { MarkupCondition, MarkupSettings, useHotelRoomMarkupSettings } from '~api/hotel/markup-settings'
import { HotelRate, useHotelRatesAPI } from '~api/hotel/price-rate'
import { HotelRoomResponse } from '~api/hotel/room'

import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'

const props = defineProps<{
  opened: MaybeRef<boolean>
  formData: Partial<RoomFormData>
  roomIndex?: number
  hotelMarkupSettings: MarkupSettings | null
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit'): void
}>()

const { bookingID, hotelID, hotelRooms } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
    hotelID: z.number(),
    hotelRooms: z.array(
      z.object({
        id: z.number(),
        hotel_id: z.number(),
        name: z.string(),
        custom_name: z.string(),
        rooms_number: z.number(),
        guests_number: z.number(),
      }),
    ),
  }),
)

const markupSettings = computed<MarkupSettings | null>(() => props.hotelMarkupSettings)

const formData = computed<RoomFormData>(() => ({
  bookingID,
  roomIndex: props.roomIndex,
  ...props.formData,
}))

const preparedRooms = computed<SelectOption[]>(
  () => hotelRooms.map((room: HotelRoomResponse) => ({ value: room.id, label: room.name })),
)

const isFetching = ref<boolean>(false)

const modalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!validateForm<RoomFormData>(modalForm as Ref<HTMLFormElement>, formData)) {
    return
  }
  isFetching.value = true
  if (formData.value.roomIndex !== undefined) {
    await updateBookingRoom(formData)
  } else {
    await addRoomToBooking(formData)
  }
  isFetching.value = false
  emit('submit')
}

const roomRatesPayload = ref<{
  hotelID: number
  roomID: number
}>({ hotelID, roomID: formData.value.id as number })

const { execute: fetchRoomRates, data: roomRates, isFetching: isRoomDataFetching } = useHotelRatesAPI(roomRatesPayload)

const preparedRoomRates = computed<SelectOption[]>(
  () => roomRates.value?.map((rate: HotelRate) => ({ value: rate.id, label: rate.name })) || [],
)

const { data: roomMarkupSettings, execute: fetchRoomMarkupSettings } = useHotelRoomMarkupSettings(roomRatesPayload)

const mapConditionToSelectOption = (condition: MarkupCondition): SelectOption => ({
  value: JSON.stringify(condition),
  label: getConditionLabel(condition),
})

const earlyCheckIn = computed<SelectOption[]>(() => markupSettings.value?.earlyCheckIn.map(mapConditionToSelectOption) || [])
const lateCheckOut = computed<SelectOption[]>(() => markupSettings.value?.lateCheckOut.map(mapConditionToSelectOption) || [])
const discounts = computed<SelectOption[]>(() => {
  if (!roomMarkupSettings.value?.discount) {
    return []
  }

  const options = []
  for (let i = 1; i <= roomMarkupSettings.value?.discount; i++) {
    options.push({ value: i, label: `${i}` })
  }
  return options
})

const handleChangeRoomId = () => {
  if (formData.value.id !== undefined) {
    roomRatesPayload.value.roomID = formData.value.id
    fetchRoomRates()
    fetchRoomMarkupSettings()
  }
}

watch(formData, (value: RoomFormData, oldValue: RoomFormData) => {
  if (value.id !== oldValue.id) {
    handleChangeRoomId()
  }
}, { deep: true })

const earlyCheckInValue = computed<string>({
  get: (): string => JSON.stringify(formData.value.earlyCheckIn),
  set: (value: string): void => {
    formData.value.earlyCheckIn = JSON.parse(value)
  },
})

const lateCheckOutValue = computed<string>({
  get: (): string => JSON.stringify(formData.value.lateCheckOut),
  set: (value: string): void => {
    formData.value.lateCheckOut = JSON.parse(value)
  },
})

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="isFetching"
    @close="$emit('close')"
  >
    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <BootstrapSelectBase
          id="room_id"
          :options="preparedRooms"
          label="Номер"
          :value="formData.id as number"
          required
          @input="value => formData.id = value as number"
          @change="handleChangeRoomId"
        />
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="status"
          :options="roomStatusOptions"
          label="Статус"
          :value="formData.status as number"
          required
          @input="value => formData.status = value as number"
        />
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="rate_id"
          :options="preparedRoomRates"
          label="Тариф"
          :value="formData.rateId as number"
          required
          :disabled="!formData.id || isRoomDataFetching"
          disabled-placeholder="Выберите номер"
          @input="value => formData.rateId = value as number"
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="resident_type"
          label="Тип стоимости"
          :options="residentTypeOptions"
          :value="formData.residentType as number"
          required
          @input="value => formData.residentType = value as number"
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="discount"
          label="Скидка"
          :options="discounts"
          :value="formData.discount as number"
          :disabled="!formData.id || isRoomDataFetching || discounts.length === 0"
          disabled-placeholder="Выберите номер"
          @input="value => formData.discount = value as number"
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="early_checkin"
          :options="earlyCheckIn"
          label="Ранний заезд"
          :value="earlyCheckInValue"
          @input="value => earlyCheckInValue = value as string"
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="late_checkout"
          :options="lateCheckOut"
          label="Поздний выезд"
          :value="lateCheckOutValue"
          @input="value => lateCheckOutValue = value as string"
        />
      </div>
      <div class="col-md-12">
        <label for="note" class=" col-form-label">Примечание</label>
        <textarea
          id="note"
          v-model="formData.note"
          class="form-control"
        />
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
