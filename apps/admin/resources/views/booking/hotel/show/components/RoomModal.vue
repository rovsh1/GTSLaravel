<script setup lang="ts">

import { computed, nextTick, Ref, ref, watch, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { requestInitialData } from 'gts-common/helpers/initial-data'
import BaseDialog from 'gts-components/Base/BaseDialog'
import SelectComponent from 'gts-components/Base/SelectComponent'
import { SelectOption } from 'gts-components/Bootstrap/lib'
import { z } from 'zod'

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

import { isDataValid, validateForm } from '~helpers/form'

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

const formDataLocale = ref<RoomFormData>(setFormData())

watchEffect(() => {
  formDataLocale.value = setFormData()
})

const validateRoomForm = computed(() => (isDataValid(null, formDataLocale.value.id)
  && isDataValid(null, formDataLocale.value.rateId)
  && isDataValid(null, formDataLocale.value.residentType)))

const roomRatesPayload = ref<{
  hotelID: number
  roomID: number
}>({ hotelID, roomID: formDataLocale.value.id as number })

const { execute: fetchRoomRates, data: roomRates, isFetching: isRoomDataFetching } = useHotelRatesAPI(roomRatesPayload)
const { data: roomMarkupSettings } = useHotelRoomMarkupSettings(roomRatesPayload) // execute: fetchRoomMarkupSettings

const isFetching = ref<boolean>(false)
const modalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!validateForm<RoomFormData>(modalForm as Ref<HTMLFormElement>, formDataLocale)
    || !validateRoomForm.value || isFetching.value) {
    return
  }
  isFetching.value = true
  let isRequestSuccess: undefined | boolean = false
  if (formDataLocale.value.roomBookingId !== undefined) {
    formDataLocale.value.earlyCheckIn = formDataLocale.value.earlyCheckIn || null
    formDataLocale.value.lateCheckOut = formDataLocale.value.lateCheckOut || null
    const responseUpdate = await updateBookingRoom(formDataLocale)
    isRequestSuccess = responseUpdate.data.value?.success
  } else {
    formDataLocale.value.earlyCheckIn = formDataLocale.value.earlyCheckIn || null
    formDataLocale.value.lateCheckOut = formDataLocale.value.lateCheckOut || null
    const responseAdd = await addRoomToBooking(formDataLocale)
    isRequestSuccess = responseAdd.data.value?.success
  }
  if (isRequestSuccess) {
    emit('submit')
  }
  fetchAvailableRooms()
  isFetching.value = false
}

const handleChangeRoomId = (value: any) => {
  formDataLocale.value.id = value as number
  if (formDataLocale.value.id !== undefined) {
    roomRatesPayload.value.roomID = formDataLocale.value.id
    fetchRoomRates()
    // fetchRoomMarkupSettings()
  } else {
    formDataLocale.value.rateId = undefined
    formDataLocale.value.discount = undefined
  }
}

watch(formDataLocale, (value: RoomFormData, oldValue: RoomFormData) => {
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
  if (formDataLocale.value?.id !== undefined
  && !availableRooms?.some((room: HotelRoomResponse) => room.id === formDataLocale.value?.id)) {
    currentRoom = hotelRooms.filter((room: HotelRoomResponse) => room.id === formDataLocale.value?.id)?.map(
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
  get: () => (formDataLocale.value.earlyCheckIn ? JSON.stringify(formDataLocale.value.earlyCheckIn) : undefined),
  set: (value: string | undefined): void => {
    if (value) {
      formDataLocale.value.earlyCheckIn = JSON.parse(value)
    } else {
      formDataLocale.value.earlyCheckIn = undefined
    }
  },
})

const lateCheckOutValue = computed<string | undefined>({
  get: () => (formDataLocale.value.lateCheckOut ? JSON.stringify(formDataLocale.value.lateCheckOut) : undefined),
  set: (value: string | undefined): void => {
    if (value) {
      formDataLocale.value.lateCheckOut = JSON.parse(value)
    } else {
      formDataLocale.value.lateCheckOut = undefined
    }
  },
})

const closeModal = () => {
  emit('close')
}

const resetForm = () => {
  emit('clear')
  formDataLocale.value.id = undefined
  formDataLocale.value.discount = undefined
  formDataLocale.value.rateId = undefined
  formDataLocale.value.earlyCheckIn = undefined
  formDataLocale.value.lateCheckOut = undefined
  formDataLocale.value.residentType = undefined
  formDataLocale.value.note = undefined
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
}

watch(() => props.opened, async (opened) => {
  if (opened) {
    await fetchAvailableRooms()
    await setPreparedRooms()
  }
})

</script>

<template>
  <BaseDialog :opened="opened as boolean" :loading="isFetching" @keydown.enter="onModalSubmit" @close="closeModal">
    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <SelectComponent
          v-if="opened"
          :options="preparedRooms"
          required
          label="Номер"
          :disabled="preparedRooms.length === 0 || hotelRoomsStore.isFetchAvailableRooms"
          disabled-placeholder="Нет доступных квот на заданный период"
          :value="formDataLocale.id"
          @change="(value, event) => {
            handleChangeRoomId(value)
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-12">
        <SelectComponent
          v-if="opened"
          :options="preparedRoomRates"
          required
          label="Тариф"
          :disabled="!formDataLocale.id || isRoomDataFetching"
          :disabled-placeholder="'Выберите номер'"
          :value="formDataLocale.rateId"
          @change="(value, event) => {
            formDataLocale.rateId = value as number
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-6">
        <SelectComponent
          v-if="opened"
          :options="residentTypeOptions"
          required
          label="Тип стоимости"
          :value="formDataLocale.residentType"
          @change="(value, event) => {
            formDataLocale.residentType = value as number
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-6">
        <SelectComponent
          v-if="opened"
          :options="discounts"
          label="Скидка"
          :value="formDataLocale.discount"
          :disabled="!formDataLocale.id || isRoomDataFetching || discounts.length === 0"
          :disabled-placeholder="!formDataLocale.id ? 'Выберите номер' : ''"
          :returned-empty-value="null"
          @change="(value) => {
            formDataLocale.discount = value as number
          }"
        />
      </div>
      <div class="col-md-6">
        <SelectComponent
          v-if="opened"
          :options="earlyCheckIn"
          label="Ранний заезд"
          :value="earlyCheckInValue"
          :allow-empty-item="true"
          @change="(value) => {
            earlyCheckInValue = value as string
          }"
        />
      </div>
      <div class="col-md-6">
        <SelectComponent
          v-if="opened"
          :options="lateCheckOut"
          label="Поздний выезд"
          :value="lateCheckOutValue"
          :allow-empty-item="true"
          @change="(value) => {
            lateCheckOutValue = value as string
          }"
        />
      </div>
      <div class="col-md-12">
        <label for="note" class=" col-form-label">Примечание</label>
        <textarea id="note" v-model="formDataLocale.note" class="form-control" />
      </div>
    </form>
    <template v-if="formDataLocale.roomBookingId === undefined" #actions-start>
      <button class="btn btn-default" type="button" :disabled="isFetching" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button class="btn btn-primary" type="button" :disabled="!validateRoomForm || isFetching" @click="onModalSubmit">
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" :disabled="isFetching" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
