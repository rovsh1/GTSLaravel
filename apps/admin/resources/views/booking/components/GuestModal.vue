<script setup lang="ts">

import { computed, nextTick, ref, unref, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import { isDataValid } from '~resources/composables/form'
import { genderOptions } from '~resources/views/hotel-booking/show/lib/constants'
import { GuestFormData } from '~resources/views/hotel-booking/show/lib/data-types'

import { Guest } from '~api/booking/order/guest'
import { CountryResponse } from '~api/country'

import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'
import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const props = withDefaults(defineProps<{
  opened: MaybeRef<boolean>
  isFetching: MaybeRef<boolean>
  roomBookingId: MaybeRef<number>
  orderId: MaybeRef<number>
  countries: CountryResponse[]
  formData: Partial<GuestFormData>
  orderGuests?: Guest[] | null
  titleText?: string
  inputSelectText?: string
  guestId?: number
}>(), {
  titleText: 'Данные гостя',
  inputSelectText: 'Гость',
  guestId: undefined,
  orderGuests: null,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'clear'): void
  (event: 'submit', operationType: string, payload: any): void
}>()

const { bookingID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
  }),
)

const ageTypeOptions: SelectOption[] = [
  { value: 0, label: 'Взрослый' },
  { value: 1, label: 'Ребенок' },
]

const setFormData = () => ({
  bookingID,
  id: props.guestId,
  ...props.formData,
  selectedGuestFromOrder: undefined,
})

const formData = ref<GuestFormData>(setFormData())

const localAgeType = ref<number>()
const ageType = computed<number>({
  get: () => {
    if (localAgeType.value !== undefined) {
      return localAgeType.value
    }

    return formData.value.isAdult ? 0 : 1
  },
  set: (type: number): void => {
    localAgeType.value = type
  },
})

const handleChangeAgeType = (type: number): void => {
  ageType.value = type
  formData.value.isAdult = type === 0
  formData.value.age = null
  nextTick(() => {
    $('#child_age').removeClass('is-invalid')
  })
}

watchEffect(() => {
  formData.value = setFormData()
  localAgeType.value = undefined
})

const validateCreateGuestForm = computed(() => (isDataValid(null, formData.value.countryId)
&& isDataValid(null, formData.value.fullName) && isDataValid(null, formData.value.gender)
&& (ageType.value === 1 ? isDataValid(null, formData.value.age) : true)))

const validateSelectGuestForm = computed(() => (isDataValid(null, formData.value.selectedGuestFromOrder)))

const isFormValid = (): boolean => {
  if (
    ((validateCreateGuestForm.value || validateSelectGuestForm.value) && formData.value.id === undefined)
        || (formData.value.id !== undefined && validateCreateGuestForm.value)
  ) {
    return true
  }
  return false
}

const submitDisable = computed(() => !isFormValid())

const onModalSubmit = async () => {
  if (!isFormValid()) {
    return
  }
  formData.value.roomBookingId = unref<number>(props.roomBookingId)
  formData.value.orderId = unref<number>(props.orderId)
  if (formData.value.id !== undefined) {
    const payload = {
      guestId: formData.value.id,
      ...formData.value,
    }
    emit('submit', 'update', payload)
  } else if (formData.value.selectedGuestFromOrder) {
    const payload = {
      bookingID,
      roomBookingId: formData.value.roomBookingId,
      guestId: formData.value.selectedGuestFromOrder,
    }
    emit('submit', 'add', payload)
  } else if (!formData.value.selectedGuestFromOrder) {
    const payload = {
      hotelBookingRoomId: formData.value.roomBookingId,
      hotelBookingId: bookingID,
      ...formData.value,
    }
    emit('submit', 'create', payload)
  }
  localAgeType.value = undefined
}

const countryOptions = computed<SelectOption[]>(
  () => props.countries?.map((country: CountryResponse) => ({ value: country.id, label: country.name })) || [],
)

const guestsOptions = computed<SelectOption[]>(
  () => props.orderGuests?.map((guest: Guest) => ({ value: guest.id, label: guest.fullName })) || [],
)

const closeModal = () => {
  ageType.value = 0
  handleChangeAgeType(ageType.value)
  emit('close')
}

const resetForm = () => {
  emit('clear')
  formData.value.countryId = undefined
  formData.value.fullName = ''
  formData.value.gender = undefined
  formData.value.age = undefined
  formData.value.selectedGuestFromOrder = undefined
  ageType.value = 0
  handleChangeAgeType(ageType.value)
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
}

const onChangeSelectGuest = (value: any) => {
  formData.value.selectedGuestFromOrder = value ? Number(value) : undefined
  const getCurrentGuestData = props.orderGuests?.filter((guest: Guest) => guest.id === formData.value.selectedGuestFromOrder)
  if (getCurrentGuestData && getCurrentGuestData.length > 0) {
    const [firstGuest] = getCurrentGuestData
    formData.value.fullName = firstGuest.fullName
    formData.value.age = firstGuest.age
    formData.value.countryId = firstGuest.countryId
    formData.value.gender = firstGuest.gender
    formData.value.isAdult = firstGuest.isAdult
    localAgeType.value = undefined
  } else {
    resetForm()
  }
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="isFetching"
    @close="closeModal"
    @keydown.enter="onModalSubmit"
  >
    <template #title>{{ titleText }}</template>
    <form class="row g-3">
      <div v-if="orderGuests && guestsOptions.length > 0 && formData.id === undefined" class="col-md-12 guest-select-wrapper">
        <Select2BaseSelect
          id="guest-select"
          :label="inputSelectText"
          :options="guestsOptions"
          :value="formData.selectedGuestFromOrder"
          parent=".guest-select-wrapper"
          :enable-tags="false"
          :show-empty-item="true"
          empty-item-text="Создать нового гостя"
          @input="onChangeSelectGuest"
        />
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="nationality_id"
          :options="countryOptions"
          label="Гражданство"
          :disabled="!!formData.selectedGuestFromOrder"
          :value="formData.countryId as number"
          required
          @blur="isDataValid($event, formData.countryId)"
          @input="(value: any) => formData.countryId = value as number"
        />
      </div>
      <div class="col-md-12">
        <div class="field-required">
          <label for="full_name">ФИО</label>
          <input
            id="full_name"
            v-model="formData.fullName"
            :disabled="!!formData.selectedGuestFromOrder"
            class="form-control"
            required
            @input="isDataValid($event, formData.fullName)"
            @blur="isDataValid($event, formData.fullName)"
          >
        </div>
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="gender"
          :options="genderOptions"
          label="Пол"
          :disabled="!!formData.selectedGuestFromOrder"
          :value="formData.gender as number"
          required
          @blur="isDataValid($event, formData.gender)"
          @input="(value: any) => formData.gender = value as number"
        />
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="age_type"
          :options="ageTypeOptions"
          label="Тип"
          :disabled="!!formData.selectedGuestFromOrder"
          :value="ageType"
          @input="(value: any) => handleChangeAgeType(Number(value))"
        />
      </div>

      <div class="col-md-12">
        <div :class="[ageType === 1 ? 'field-required' : '']">
          <label for="child_age">Возраст</label>
          <input
            id="child_age"
            v-model="formData.age"
            :disabled="!!formData.selectedGuestFromOrder || formData.isAdult || isNaN(ageType)"
            type="number"
            class="form-control"
            autocomplete="off"
            :required="formData.isAdult === false"
            min="0"
            max="18"
            @blur="isDataValid($event, formData.age)"
          >
        </div>
      </div>
    </form>
    <template v-if="formData.id === undefined" #actions-start>
      <button class="btn btn-default" type="button" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="submitDisable"
        @click="onModalSubmit"
      >
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>

<style lang="scss" scoped>
.guest-select-wrapper {
  position: relative;
}
</style>
