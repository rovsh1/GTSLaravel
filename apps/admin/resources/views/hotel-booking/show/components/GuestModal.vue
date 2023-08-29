<script setup lang="ts">

import { computed, Ref, ref, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import { validateForm } from '~resources/composables/form'
import { genderOptions } from '~resources/views/hotel-booking/show/lib/constants'
import { GuestFormData } from '~resources/views/hotel-booking/show/lib/data-types'
import { useOrderStore } from '~resources/views/hotel-booking/show/store/order-currency'

import { addGuestToBooking } from '~api/booking/hotel/rooms'
import { addOrderTourist, updateOrderTourist } from '~api/booking/order/tourists'
import { CountryResponse } from '~api/country'

import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'

const props = defineProps<{
  opened: MaybeRef<boolean>
  roomBookingId: MaybeRef<number>
  countries: CountryResponse[]
  formData: Partial<GuestFormData>
  guestId?: number
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit'): void
}>()

const { bookingID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
  }),
)

const orderStore = useOrderStore()
const orderId = computed(() => orderStore.order.id)

const ageTypeOptions: SelectOption[] = [
  { value: 0, label: 'Взрослый' },
  { value: 1, label: 'Ребенок' },
]

const localAgeType = ref<number>()
const ageType = computed<number>({
  get: () => {
    if (localAgeType.value !== undefined) {
      return localAgeType.value
    }

    return props.formData.isAdult ? 0 : 1
  },
  set: (type: number): void => {
    localAgeType.value = type
  },
})

const formData = computed<GuestFormData>(() => ({
  bookingID,
  id: props.guestId,
  ...props.formData,
}))

const isFetching = ref<boolean>(false)

const modalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!validateForm<GuestFormData>(modalForm as Ref<HTMLFormElement>, formData)) {
    return
  }
  isFetching.value = true
  formData.value.roomBookingId = unref<number>(props.roomBookingId)
  formData.value.orderId = orderId.value
  if (formData.value.id !== undefined) {
    const payload = {
      touristId: formData.value.id,
      ...formData.value,
    }
    await updateOrderTourist(payload)
  } else {
    const { data: tourist } = await addOrderTourist(formData)
    // @todo добавить возможность выбрать из списка туристов заказа,
    // @todo перенести в контроллер
    await addGuestToBooking({
      guestId: tourist.value?.id as number,
      bookingID,
      roomBookingId: formData.value.roomBookingId,
    })
  }
  localAgeType.value = undefined
  isFetching.value = false
  emit('submit')
}

const countryOptions = computed<SelectOption[]>(
  () => props.countries?.map((country: CountryResponse) => ({ value: country.id, label: country.name })) || [],
)

const handleChangeAgeType = (type: number): void => {
  ageType.value = type
  formData.value.isAdult = type === 0
  formData.value.age = null
}

const closeModal = () => {
  modalForm.value?.reset()
  emit('close')
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="isFetching"
    @close="closeModal"
    @keydown.enter="onModalSubmit"
  >
    <template #title>Данные гостя</template>

    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <BootstrapSelectBase
          id="nationality_id"
          :options="countryOptions"
          label="Гражданство"
          :value="formData.countryId as number"
          required
          @input="value => formData.countryId = value as number"
        />
      </div>
      <div class="col-md-12">
        <div class="field-required">
          <label for="full_name">ФИО</label>
          <input id="full_name" v-model="formData.fullName" class="form-control" required>
        </div>
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="gender"
          :options="genderOptions"
          label="Пол"
          :value="formData.gender as number"
          required
          @input="value => formData.gender = value as number"
        />
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="age_type"
          :options="ageTypeOptions"
          label="Тип"
          :value="ageType"
          @input="value => handleChangeAgeType(Number(value))"
        />
      </div>

      <div v-if="!formData.isAdult" class="col-md-12">
        <div class="field-required">
          <label for="child_age">Возраст</label>
          <input
            id="child_age"
            v-model="formData.age"
            type="number"
            class="form-control"
            autocomplete="off"
            required
            min="0"
            max="18"
          >
        </div>
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
