<script setup lang="ts">

import { computed, nextTick, Ref, ref, watch, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import { isDataValid, validateForm } from '~resources/composables/form'
import { useHotelRoomsStore } from '~resources/views/booking/hotel/show/store/hotel-rooms'
import {
  getConditionLabel,
  mapEntitiesToSelectOptions,
} from '~resources/views/booking/shared/lib/constants'
import { RoomFormData } from '~resources/views/booking/shared/lib/data-types'

import { addRoomToBooking, updateBookingRoom } from '~api/booking/hotel/rooms'
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
  roomBookingId?: number
  hotelMarkupSettings: MarkupSettings | null
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'clear'): void
  (event: 'submit'): void
}>()

const { bookingID, hotelID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
    hotelID: z.number(),
  }),
)

const hotelRoomsStore = useHotelRoomsStore()

const { fetchAvailableRooms } = hotelRoomsStore

const residentTypeOptions = mapEntitiesToSelectOptions([
  { id: 1, name: 'Резидент' },
  { id: 0, name: 'Не резидент' },
])

const setFormData = () => ({
  bookingID,
  roomBookingId: props.roomBookingId,
  ...props.formData,
})

const formData = ref<RoomFormData>(setFormData())

watchEffect(() => {
  formData.value = setFormData()
})

const validateRoomForm = computed(() => (isDataValid(null, formData.value.id)
&& isDataValid(null, formData.value.rateId)
&& isDataValid(null, formData.value.residentType)))

const roomRatesPayload = ref<{
  hotelID: number
  roomID: number
}>({ hotelID, roomID: formData.value.id as number })

const { execute: fetchRoomRates, data: roomRates, isFetching: isRoomDataFetching } = useHotelRatesAPI(roomRatesPayload)
const { data: roomMarkupSettings, execute: fetchRoomMarkupSettings } = useHotelRoomMarkupSettings(roomRatesPayload)

const isFetching = ref<boolean>(false)
const modalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!validateForm<RoomFormData>(modalForm as Ref<HTMLFormElement>, formData)) {
    return
  }
  isFetching.value = true
  let isRequestSuccess: undefined | boolean = false
  if (formData.value.roomBookingId !== undefined) {
    const responseUpdate = await updateBookingRoom(formData)
    isRequestSuccess = responseUpdate.data.value?.success
  } else {
    const responseAdd = await addRoomToBooking(formData)
    isRequestSuccess = responseAdd.data.value?.success
  }
  if (isRequestSuccess) {
    emit('submit')
  }
  fetchAvailableRooms()
  isFetching.value = false
}

const handleChangeRoomId = (value: any) => {
  formData.value.id = value as number
  if (formData.value.id !== undefined) {
    roomRatesPayload.value.roomID = formData.value.id
    fetchRoomRates()
    // fetchRoomMarkupSettings()
  } else {
    formData.value.rateId = undefined
    formData.value.discount = undefined
  }
}

watch(formData, (value: RoomFormData, oldValue: RoomFormData) => {
  if (value.id !== oldValue.id) {
    handleChangeRoomId(value.id)
  }
}, { deep: true })

const mapConditionToSelectOption = (condition: MarkupCondition): SelectOption => ({
  value: JSON.stringify(condition),
  label: getConditionLabel(condition),
})
const markupSettings = computed<MarkupSettings | null>(() => props.hotelMarkupSettings)

const preparedRooms = ref<SelectOption[]>([])

const setPreparedRooms = () => {
  const { availableRooms, hotelRooms } = hotelRoomsStore
  let currentRoom: SelectOption[] = []
  const availableRoomsOptions = availableRooms?.map(
    (room: HotelRoomResponse) => ({ value: room.id, label: room.name }),
  ) || []
  if (formData.value?.id !== undefined && !availableRooms?.some((room: HotelRoomResponse) => room.id === formData.value?.id)) {
    currentRoom = hotelRooms.filter((room: HotelRoomResponse) => room.id === formData.value?.id)?.map(
      (room: HotelRoomResponse) => ({ value: room.id, label: room.name }),
    ) || []
  }
  preparedRooms.value = [...currentRoom, ...availableRoomsOptions]
}

const preparedRoomRates = computed<SelectOption[]>(
  () => roomRates.value?.map((rate: HotelRate) => ({ value: rate.id, label: rate.name })) || [],
)
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

const earlyCheckInValue = computed<string | undefined>({
  get: () => (formData.value.earlyCheckIn ? JSON.stringify(formData.value.earlyCheckIn) : undefined),
  set: (value: string | undefined): void => {
    if (value) {
      formData.value.earlyCheckIn = JSON.parse(value)
    } else {
      formData.value.earlyCheckIn = undefined
    }
  },
})

const lateCheckOutValue = computed<string | undefined>({
  get: () => (formData.value.lateCheckOut ? JSON.stringify(formData.value.lateCheckOut) : undefined),
  set: (value: string | undefined): void => {
    if (value) {
      formData.value.lateCheckOut = JSON.parse(value)
    } else {
      formData.value.lateCheckOut = undefined
    }
  },
})

const closeModal = () => {
  emit('close')
}

const resetForm = () => {
  emit('clear')
  formData.value.id = undefined
  formData.value.discount = undefined
  formData.value.rateId = undefined
  formData.value.earlyCheckIn = undefined
  formData.value.lateCheckOut = undefined
  formData.value.residentType = undefined
  formData.value.note = undefined
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
}

watch(() => props.opened, async (opened) => {
  if (opened) {
    await fetchAvailableRooms()
    setPreparedRooms()
  }
})

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="isFetching"
    @close="closeModal"
    @keydown.enter="onModalSubmit"
  >
    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <BootstrapSelectBase
          id="room_id"
          :options="preparedRooms"
          label="Номер"
          :value="formData.id as number"
          required
          :disabled="preparedRooms.length === 0"
          disabled-placeholder="Нет доступных квот на заданный период"
          @input="(value, event) => {
            handleChangeRoomId(value)
            isDataValid(event, value)
          }"
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
          :disabled-placeholder="!formData.id ? 'Выберите номер' : ''"
          @input="(value, event) => {
            formData.rateId = value as number
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="resident_type"
          label="Тип стоимости"
          :options="residentTypeOptions"
          :value="formData.residentType as number"
          required
          @input="(value, event) => {
            formData.residentType = value as number
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="discount"
          label="Скидка"
          :options="discounts"
          :value="formData.discount as number"
          :disabled="!formData.id || isRoomDataFetching || discounts.length === 0"
          :disabled-placeholder="!formData.id ? 'Выберите номер' : ''"
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
    <template v-if="formData.roomBookingId === undefined" #actions-start>
      <button class="btn btn-default" type="button" :disabled="isFetching" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="!validateRoomForm || isFetching"
        @click="onModalSubmit"
      >
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" :disabled="isFetching" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
